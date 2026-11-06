<?php

namespace App\Http\Controllers;

use App\Models\MoldRequest;
use App\Models\MoldRequestStage;
use App\Models\MoldRequestMaterial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MoldRequestController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $baseQuery = MoldRequest::query();

        // KPI metrics
        $totalSpk = (clone $baseQuery)->count();
        $inProgressSpk = (clone $baseQuery)->where('status', 'In Progress')->count();
        $completedSpk = (clone $baseQuery)->where('status', 'Completed')->count();
        $draftSpk = (clone $baseQuery)->where('status', 'Draft')->count();
        $avgProgress = (int) round((clone $baseQuery)->avg('total_progress') ?? 0);

        $query = MoldRequest::with(['stages', 'materials', 'creator']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('spk_number', 'like', "%{$search}%")
                  ->orWhere('mold_name', 'like', "%{$search}%")
                  ->orWhere('customer', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $moldRequests = $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

        return view('mold-requests.index', compact(
            'moldRequests',
            'search',
            'statusFilter',
            'perPage',
            'totalSpk',
            'inProgressSpk',
            'completedSpk',
            'draftSpk',
            'avgProgress'
        ));
    }

    public function store(Request $request)
    {
        $spkNumber = trim($request->spk_number ?? '');
        if (empty($spkNumber)) {
            $yearMonth = Carbon::now()->format('Ym');
            $randSeq = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $spkNumber = "SPK-{$yearMonth}-{$randSeq}";
            $request->merge(['spk_number' => $spkNumber]);
        }

        $request->validate([
            'spk_number' => 'required|string|unique:mold_requests,spk_number|max:100',
            'mold_name' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'pic_name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'target_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        $moldRequest = MoldRequest::create([
            'spk_number' => $spkNumber,
            'mold_name' => trim($request->mold_name),
            'customer' => trim($request->customer),
            'pic_name' => $request->pic_name ? trim($request->pic_name) : null,
            'start_date' => $request->start_date,
            'target_date' => $request->target_date,
            'status' => 'In Progress',
            'total_progress' => 0,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        // Auto-generate 5 standard stages per BRD Page 10 (Desain -> Machining -> Assembly -> Trial -> Finishing)
        $standardStages = [
            ['name' => 'Desain', 'order' => 1, 'weight' => 20],
            ['name' => 'Machining', 'order' => 2, 'weight' => 30],
            ['name' => 'Assembly', 'order' => 3, 'weight' => 20],
            ['name' => 'Trial', 'order' => 4, 'weight' => 15],
            ['name' => 'Finishing', 'order' => 5, 'weight' => 15],
        ];

        foreach ($standardStages as $stage) {
            MoldRequestStage::create([
                'mold_request_id' => $moldRequest->id,
                'stage_name' => $stage['name'],
                'stage_order' => $stage['order'],
                'weight_percent' => $stage['weight'],
                'status' => 'Pending',
            ]);
        }

        return redirect()->route('mold-requests.show', $moldRequest->id)
            ->with('success', "SPK Pembuatan Mold Baru {$moldRequest->spk_number} berhasil diterbitkan!");
    }

    public function show($id): View
    {
        $moldRequest = MoldRequest::with(['stages', 'materials', 'creator'])->findOrFail($id);

        return view('mold-requests.show', compact('moldRequest'));
    }

    public function updateStage(Request $request, $id, $stageId)
    {
        $moldRequest = MoldRequest::findOrFail($id);
        $stage = MoldRequestStage::where('mold_request_id', $moldRequest->id)->findOrFail($stageId);

        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'notes' => 'nullable|string',
        ]);

        $stage->status = $request->status;
        $stage->notes = $request->notes;
        $stage->completed_at = ($request->status === 'Completed') ? Carbon::now() : null;
        $stage->save();

        // Hitung ulang progress otomatis
        $moldRequest->recalculateProgress();

        return back()->with('success', "Tahapan {$stage->stage_name} berhasil diperbarui! Progress SPK saat ini: {$moldRequest->total_progress}%.");
    }

    public function storeMaterial(Request $request, $id)
    {
        $moldRequest = MoldRequest::findOrFail($id);

        $request->validate([
            'part_name' => 'required|string|max:255',
            'required_qty' => 'required|integer|min:1',
            'available_qty' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Menunggu Pengadaan,Selesai',
            'notes' => 'nullable|string',
        ]);

        MoldRequestMaterial::create([
            'mold_request_id' => $moldRequest->id,
            'part_name' => trim($request->part_name),
            'required_qty' => $request->required_qty,
            'available_qty' => $request->available_qty,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', "Item part/material berhasil ditambahkan ke checklist SPK!");
    }

    public function destroyMaterial($id, $materialId)
    {
        $moldRequest = MoldRequest::findOrFail($id);
        $material = MoldRequestMaterial::where('mold_request_id', $moldRequest->id)->findOrFail($materialId);
        $material->delete();

        return back()->with('success', 'Item material berhasil dihapus dari checklist.');
    }

    public function destroy($id)
    {
        $moldRequest = MoldRequest::findOrFail($id);
        $moldRequest->delete();

        return redirect()->route('mold-requests.index')->with('success', 'Data SPK berhasil dihapus.');
    }
}
