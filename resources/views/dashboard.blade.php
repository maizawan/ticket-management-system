@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="mb-4 fw-bold">Dashboard</h2>

    <div class="row g-4">

        <!-- Total Tickets -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <h5 class="mb-3">Total Tickets</h5>
                <h1 class="fw-bold text-dark">
                    {{ $totalTickets ?? 0 }}
                </h1>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 bg-warning text-white">
                <h5 class="mb-3">Open</h5>
                <h1 class="fw-bold">
                    {{ $openTickets ?? 0 }}
                </h1>
            </div>
        </div>

        <!-- In Progress -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 bg-primary text-white">
                <h5 class="mb-3">In Progress</h5>
                <h1 class="fw-bold">
                    {{ $inProgressTickets ?? 0 }}
                </h1>
            </div>
        </div>

        <!-- Closed -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 bg-success text-white">
                <h5 class="mb-3">Closed</h5>
                <h1 class="fw-bold">
                    {{ $closedTickets ?? 0 }}
                </h1>
            </div>
        </div>

    </div>

</div>

@endsection