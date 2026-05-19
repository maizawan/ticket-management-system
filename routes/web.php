<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Models\Ticket;

/*
|--------------------------------------------------------------------------
| HOME REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/dashboard');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (ALL USERS LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {

    $user = auth()->user();

    // 👤 USER: only own tickets
    if ($user && $user->role !== 'admin') {

        $totalTickets = Ticket::where('user_id', $user->id)->count();

        $openTickets = Ticket::where('user_id', $user->id)->where('status', 'open')->count();

        $inProgressTickets = Ticket::where('user_id', $user->id)->where('status', 'in_progress')->count();

        $closedTickets = Ticket::where('user_id', $user->id)->where('status', 'closed')->count();

    } else {

        // 👑 ADMIN: all tickets
        $totalTickets = Ticket::count();

        $openTickets = Ticket::where('status', 'open')->count();

        $inProgressTickets = Ticket::where('status', 'in_progress')->count();

        $closedTickets = Ticket::where('status', 'closed')->count();
    }

    return view('dashboard', compact(
        'totalTickets',
        'openTickets',
        'inProgressTickets',
        'closedTickets'
    ));

})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets
    Route::resource('tickets', TicketController::class);

    // Comment (USER + ADMIN both)
    Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])
        ->name('tickets.comment');

    // Status (ONLY ADMIN)
    Route::post('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->middleware('admin')
        ->name('tickets.status');

});

require __DIR__.'/auth.php';