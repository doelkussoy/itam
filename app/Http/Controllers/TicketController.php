<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['employee', 'asset']);
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('ticket_number', 'like', "%$search%")
                  ->orWhere('title', 'like', "%$search%")
                  ->orWhereHas('employee', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest()->paginate(10)->appends($request->all());
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        $assets = Asset::orderBy('name')->get();
        return view('tickets.create', compact('employees', 'assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'employee_id' => 'required|exists:employees,id',
            'asset_id' => 'nullable|exists:assets,id',
            'priority' => 'required|in:Low,Medium,High,Critical'
        ]);

        Ticket::create([
            'ticket_number' => Ticket::generateTicketNumber(),
            'title' => $request->title,
            'description' => $request->description,
            'employee_id' => $request->employee_id,
            'asset_id' => $request->asset_id,
            'priority' => $request->priority,
            'status' => 'Open'
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function edit(Ticket $ticket)
    {
        $employees = Employee::orderBy('name')->get();
        $assets = Asset::orderBy('name')->get();
        return view('tickets.edit', compact('ticket', 'employees', 'assets'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'employee_id' => 'required|exists:employees,id',
            'asset_id' => 'nullable|exists:assets,id',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'status' => 'required|in:Open,In Progress,Resolved,Closed'
        ]);

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'employee_id' => $request->employee_id,
            'asset_id' => $request->asset_id,
            'priority' => $request->priority,
            'status' => $request->status
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new TicketExport, 'tickets.xlsx');
    }
}
