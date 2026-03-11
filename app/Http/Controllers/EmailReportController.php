<?php

namespace App\Http\Controllers;

use App\Models\EmailReport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailReportController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = EmailReport::query();

        if ($search) {
            $query->where('email', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
        }

        $emails = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('admin.email-reports.index', compact('emails', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:email_reports,email',
            'full_name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        EmailReport::create($request->only('email', 'full_name', 'status'));

        return back()->with('success', 'Data Email Penerima berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $emailReport = EmailReport::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:email_reports,email,' . $emailReport->id,
            'full_name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $emailReport->update($request->only('email', 'full_name', 'status'));

        return back()->with('success', 'Data Email Penerima berhasil diperbarui!');
    }

    public function destroy($id)
    {
        EmailReport::findOrFail($id)->delete();
        return back()->with('success', 'Data Email Penerima berhasil dihapus!');
    }
}