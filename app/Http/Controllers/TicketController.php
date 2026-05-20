<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    

    private function user()
    {
        return auth()->user();
    }


  

    private function ensureUser()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        return $user;
    }


   

    private function isAdmin($user)
    {
        return $user->role === 'admin';
    }


   

    private function canEditTicket($user, $ticket)
    {
        return (
            $ticket->user_id == $user->id &&
            strtolower(trim($ticket->status ?? '')) == 'open'
        );
    }


    

    public function index()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        $tickets = $this->isAdmin($user)
            ? Ticket::latest()->get()
            : Ticket::where('user_id', $user->id)->latest()->get();

        return view('tickets.index', compact('tickets'));
    }


  
    public function create()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        // ❌ Admin cannot create ticket
        if ($this->isAdmin($user)) {
            return redirect('/tickets')
                ->with('error', 'Admin cannot create tickets');
        }

        return view('tickets.create');
    }


  

    public function store(Request $request)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        
        if ($this->isAdmin($user)) {
            return redirect('/tickets')
                ->with('error', 'Admin cannot create tickets');
        }

        $request->validate([
            'title' => 'required|max:150',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high'
        ]);

        Ticket::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open'
        ]);

        return redirect('/tickets')
            ->with('success', 'Ticket created successfully');
    }


   
    public function show(Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        if (
            $this->isAdmin($user) ||
            $ticket->user_id == $user->id
        ) {
            return view('tickets.show', compact('ticket'));
        }

        abort(403);
    }


    

    public function edit(Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        
        if (!$this->canEditTicket($user, $ticket)) {

            return redirect('/tickets')
                ->with('error', 'You cannot edit this ticket');
        }

        return view('tickets.edit', compact('ticket'));
    }


   

    public function update(Request $request, Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        if (!$this->canEditTicket($user, $ticket)) {

            return redirect('/tickets')
                ->with('error', 'You cannot update this ticket');
        }

        $request->validate([
            'title' => 'required|max:150',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high'
        ]);

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return redirect('/tickets')
            ->with('success', 'Ticket updated successfully');
    }


  

    public function destroy(Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        
        if ($ticket->user_id != $user->id) {
            abort(403);
        }

        $ticket->delete();

        return redirect('/tickets')
            ->with('success', 'Ticket deleted successfully');
    }



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

        return back()
            ->with('success', 'Comment added successfully');
    }


   

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

       
        if (!$this->isAdmin($user)) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,closed'
        ]);

        $ticket->update([
            'status' => $request->status
        ]);

        return back()
            ->with('success', 'Status updated successfully');
    }
}