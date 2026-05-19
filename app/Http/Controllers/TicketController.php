<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketComment;

class TicketController extends Controller
{
    // 🔐 GET USER SAFELY
    private function user()
    {
        return auth()->user();
    }

   
    public function index()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($user->role === 'admin') {
            $tickets = Ticket::latest()->get();
        } else {
            $tickets = Ticket::where('user_id', $user->id)->latest()->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    
    public function create()
    {
        return view('tickets.create');
    }

    
    public function store(Request $request)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'priority' => 'required'
        ]);

        Ticket::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open'
        ]);

        return redirect('/tickets')->with('success', 'Ticket created successfully');
    }

    
    public function show(Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($user->role !== 'admin' && $ticket->user_id !== $user->id) {
            abort(403);
        }

        return view('tickets.show', compact('ticket'));
    }

    
    public function edit(Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($ticket->user_id !== $user->id || $ticket->status !== 'open') {
            abort(403);
        }

        return view('tickets.edit', compact('ticket'));
    }

   
    public function update(Request $request, Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($ticket->user_id !== $user->id || $ticket->status !== 'open') {
            abort(403);
        }

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return redirect('/tickets')->with('success', 'Ticket updated');
    }

    public function destroy(Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($ticket->user_id !== $user->id) {
            abort(403);
        }

        $ticket->delete();

        return redirect('/tickets')->with('success', 'Ticket deleted');
    }

    // Add Comment
    public function addComment(Request $request, Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'comment' => 'required'
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Comment added successfully');
    }

    
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = $this->user();

        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,closed'
        ]);

        $ticket->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Ticket status updated successfully');
    }
}