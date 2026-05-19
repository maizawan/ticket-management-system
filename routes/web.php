<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Models\Ticket;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {

    $user = auth()->user();

    if ($user && $user->role !== 'admin') {

        $totalTickets = Ticket::where('user_id', $user->id)->count();

        $openTickets = Ticket::where('user_id', $user->id)
            ->where('status', 'open')
            ->count();

        $inProgressTickets = Ticket::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->count();

        $closedTickets = Ticket::where('user_id', $user->id)
            ->where('status', 'closed')
            ->count();

    } else {

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


Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tickets', TicketController::class);

    Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])
        ->name('tickets.comment');

    /**
     * 🔥 IMPORTANT FIX:
     * admin middleware yahan risky hai if custom middleware properly tested nahi
     * better: handle admin check inside controller (you already did)
     */
    Route::post('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->name('tickets.status');

});

require __DIR__.'/auth.php';