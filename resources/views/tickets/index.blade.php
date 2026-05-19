@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Tickets</h2>

        @if(auth()->check() && auth()->user()->role !== 'admin')
    <a href="{{ url('/tickets/create') }}" class="btn btn-primary">
        + Create Ticket
    </a>
@endif

    </div>

    <table class="table table-bordered table-hover">

        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        @forelse($tickets as $ticket)

            <tr>
                <td>{{ $ticket->title }}</td>

                <td>
                    <span class="badge bg-secondary">
                        {{ $ticket->priority }}
                    </span>
                </td>

                <td>
                    <span class="badge bg-info text-dark">
                        {{ $ticket->status }}
                    </span>
                </td>

                <td>

                    <a href="/tickets/{{ $ticket->id }}"
                       class="btn btn-sm btn-info">
                        View
                    </a>

                    @if($ticket->status === 'open')

                       @if(auth()->check() 
    && auth()->user()->role !== 'admin' 
    && $ticket->status === 'open' 
    && $ticket->user_id === auth()->id())

    <a href="/tickets/{{ $ticket->id }}/edit"
       class="btn btn-sm btn-warning">
        Edit
    </a>

@endif

                        <form action="/tickets/{{ $ticket->id }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this ticket?')">
                                Delete
                            </button>

                        </form>

                    @endif

                </td>
            </tr>

        @empty

            <tr>
                <td colspan="4" class="text-center text-muted">
                    No tickets found
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection