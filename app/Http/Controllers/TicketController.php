<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketComment;

class TicketController extends Controller
{

    // All Tickets
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->role === 'admin') {
            $tickets = Ticket::latest()->get();
        } else {
            $tickets = Ticket::where('user_id', $user->id)->latest()->get();
        }

        return view('tickets.index', compact('tickets'));
    }


    // Create Form
    public function create()
    {
        return view('tickets.create');
    }


    // Store Ticket
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'priority' => 'required'
        ]);

        Ticket::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open'
        ]);

        return redirect('/tickets')->with('success', 'Ticket created successfully');
    }


    // Show Single Ticket
    public function show(Ticket $ticket)
    {
        if (auth()->user()->role !== 'admin' && $ticket->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tickets.show', compact('ticket'));
    }


    // Edit Ticket
    public function edit(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() || $ticket->status !== 'open') {
            abort(403);
        }

        return view('tickets.edit', compact('ticket'));
    }


    // Update Ticket
    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() || $ticket->status !== 'open') {
            abort(403);
        }

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return redirect('/tickets')->with('success', 'Ticket updated');
    }


    // Delete Ticket
    public function destroy(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->delete();

        return redirect('/tickets')->with('success', 'Ticket deleted');
    }


    // Add Comment
    public function addComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment' => 'required'
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Comment added successfully');
    }


    // Update Status
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $ticket->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Ticket status updated successfully');
    }
}