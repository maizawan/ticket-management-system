@extends('layouts.app')

@section('content')

<div class="container">

    <!-- Ticket Details -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <h2 class="fw-bold mb-3">
                {{ $ticket->title }}
            </h2>

            <p class="text-muted">
                {{ $ticket->description }}
            </p>

            <div class="row mt-4">

                <div class="col-md-4">
                    <div class="alert alert-secondary">
                        <b>Priority:</b>
                        {{ ucfirst($ticket->priority) }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="alert alert-info">
                        <b>Status:</b>
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="alert alert-dark">
                        <b>Created By:</b>
                        {{ $ticket->user->name ?? 'User' }}
                    </div>
                </div>

            </div>

        </div>
    </div>



    <!-- Comments Section -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">
            <h4 class="mb-0">Comments</h4>
        </div>

        <div class="card-body">

            @forelse($ticket->comments as $comment)

                <div class="border rounded p-3 mb-3">

                    <div class="d-flex justify-content-between">

                        <strong>
                            {{ $comment->user->name }}
                        </strong>

                        <small class="text-muted">
                            {{ $comment->created_at->diffForHumans() }}
                        </small>

                    </div>

                    <p class="mt-2 mb-0">
                        {{ $comment->comment }}
                    </p>

                </div>

            @empty

                <p class="text-muted">
                    No comments yet.
                </p>

            @endforelse

        </div>

    </div>



    <!-- Add Comment -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">
            <h4 class="mb-0">Add Comment</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('tickets.comment', $ticket->id) }}">

                @csrf

                <textarea
                    name="comment"
                    class="form-control mb-3"
                    rows="4"
                    placeholder="Write your comment here..."
                    required
                ></textarea>

                <button class="btn btn-primary">
                    Send Comment
                </button>

            </form>

        </div>

    </div>



    <!-- Admin Panel -->
    @if(auth()->user()->role === 'admin')

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h4 class="mb-0">Admin Panel</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('tickets.status', $ticket->id) }}">

                @csrf

                <label class="mb-2">
                    Update Ticket Status
                </label>

                <select name="status" class="form-control mb-3">

                    <option value="open"
                        {{ $ticket->status == 'open' ? 'selected' : '' }}>
                        Open
                    </option>

                    <option value="in_progress"
                        {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>
                        In Progress
                    </option>

                    <option value="closed"
                        {{ $ticket->status == 'closed' ? 'selected' : '' }}>
                        Closed
                    </option>

                </select>

                <button class="btn btn-warning">
                    Update Status
                </button>

            </form>

        </div>

    </div>

    @endif

</div>

@endsection