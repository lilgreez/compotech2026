<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        // Filter by table
        if ($request->filled('table_name')) {
            $query->forTable($request->table_name);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->inDateRange($request->start_date, $request->end_date);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get unique table names for filter dropdown
        $tableNames = AuditLog::distinct('table_name')->pluck('table_name');

        // Get users for filter dropdown
        $users = \App\Models\User::select('id', 'name')->get();

        return view('admin.audit-logs.index', compact('auditLogs', 'tableNames', 'users'));
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog): View
    {
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}