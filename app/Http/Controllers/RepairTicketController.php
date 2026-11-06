<?php

namespace App\Http\Controllers;

use App\Models\RepairTicket;
use App\Models\RepairTicketLog;
use App\Models\MasterDieset;
use App\Models\MasterPart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RepairTicketController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all');
        $priorityFilter = $request->input('priority', 'all');

        $baseQuery = RepairTicket::query();

        // KPI Metrics
        $totalTickets = (clone $baseQuery)->count();
        $openTickets = (clone $baseQuery)->where('status', 'Open')->count();
        $inProgressTickets = (clone $baseQuery)->whereIn('status', ['In Progress', 'Pending Part'])->count();
        $resolvedTickets = (clone $baseQuery)->whereIn('status', ['Resolved', 'Closed'])->count();

        $query = RepairTicket::with(['dieset', 'part', 'reporter', 'technician']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('issue_description', 'like', "%{$search}%")
                  ->orWhereHas('dieset', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($priorityFilter !== 'all') {
            $query->where('priority', $priorityFilter);
        }

        $tickets = $query->orderBy('id', 'desc')->paginate(10);
        $molds = MasterDieset::orderBy('name', 'asc')->get(['id', 'name']);
        $technicians = User::orderBy('name', 'asc')->get(['id', 'name']);

        return view('repair-tickets.index', compact(
            'tickets',
            'search',
            'statusFilter',
            'priorityFilter',
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'molds',
            'technicians'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dieset_id' => 'required|exists:master_diesets,id',
            'part_id' => 'nullable|exists:master_parts,id',
            'issue_description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'evidence_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $photoPath = null;
        if ($request->hasFile('evidence_photo')) {
            $photoPath = $request->file('evidence_photo')->store('repair_tickets', 'public');
        }

        // Generate Ticket Number: TKT-YYYYMMDD-XXXX
        $dateCode = Carbon::now()->format('Ymd');
        $randomSeq = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $ticketNumber = "TKT-{$dateCode}-{$randomSeq}";

        $ticket = RepairTicket::create([
            'ticket_number' => $ticketNumber,
            'dieset_id' => $request->dieset_id,
            'part_id' => $request->part_id,
            'reporter_user_id' => Auth::id(),
            'technician_user_id' => null,
            'issue_description' => trim($request->issue_description),
            'priority' => $request->priority,
            'evidence_photo' => $photoPath,
            'status' => 'Open',
            'progress_percent' => 0,
        ]);

        RepairTicketLog::create([
            'repair_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'Tiket Dibuat',
            'note' => "Pengajuan tiket perbaikan mold {$ticket->dieset->name} dengan prioritas {$ticket->priority}",
        ]);

        return redirect()->route('repair-tickets.show', $ticket->id)
            ->with('success', "Tiket perbaikan {$ticket->ticket_number} berhasil diajukan!");
    }

    public function show($id): View
    {
        $ticket = RepairTicket::with(['dieset', 'part', 'reporter', 'technician', 'logs.user'])->findOrFail($id);
        $technicians = User::orderBy('name', 'asc')->get(['id', 'name']);

        return view('repair-tickets.show', compact('ticket', 'technicians'));
    }

    public function assignTechnician(Request $request, $id)
    {
        $ticket = RepairTicket::findOrFail($id);

        $request->validate([
            'technician_user_id' => 'required|exists:users,id',
            'note' => 'nullable|string',
        ]);

        $technician = User::findOrFail($request->technician_user_id);
        $ticket->technician_user_id = $technician->id;
        if ($ticket->status === 'Open') {
            $ticket->status = 'In Progress';
        }
        $ticket->save();

        RepairTicketLog::create([
            'repair_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'Penugasan Teknisi',
            'note' => "Tiket ditugaskan kepada teknisi {$technician->name}." . ($request->note ? " Catatan: {$request->note}" : ""),
        ]);

        return back()->with('success', "Teknisi penanggung jawab {$technician->name} berhasil ditugaskan!");
    }

    public function updateProgress(Request $request, $id)
    {
        $ticket = RepairTicket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Open,In Progress,Pending Part,Resolved,Closed',
            'progress_percent' => 'required|integer|min:0|max:100',
            'technician_notes' => 'nullable|string',
        ]);

        $oldStatus = $ticket->status;
        $ticket->status = $request->status;
        $ticket->progress_percent = $request->progress_percent;
        $ticket->technician_notes = $request->technician_notes;

        if ($request->status === 'Resolved' && !$ticket->resolved_at) {
            $ticket->resolved_at = Carbon::now();
        }

        $ticket->save();

        RepairTicketLog::create([
            'repair_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'Update Progress',
            'note' => "Status diubah dari [{$oldStatus}] ke [{$ticket->status}], progress: {$ticket->progress_percent}%." . ($request->technician_notes ? " Catatan teknisi: {$request->technician_notes}" : ""),
        ]);

        return back()->with('success', 'Progress perbaikan tiket berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ticket = RepairTicket::findOrFail($id);
        if ($ticket->evidence_photo && Storage::disk('public')->exists($ticket->evidence_photo)) {
            Storage::disk('public')->delete($ticket->evidence_photo);
        }
        $ticket->delete();

        return redirect()->route('repair-tickets.index')->with('success', 'Tiket perbaikan berhasil dihapus.');
    }
}
