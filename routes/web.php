<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Models\Ticket;





Route::get('/', function () {
    return redirect('/dashboard');
});





Route::get('/dashboard', function () {

    $totalTickets = Ticket::count();

    $openTickets = Ticket::where('status', 'open')->count();

    $inProgressTickets = Ticket::where('status', 'in_progress')->count();

    $closedTickets = Ticket::where('status', 'closed')->count();

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


 
    Route::post('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->name('tickets.status');


   
    Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])
        ->name('tickets.comment');

});





require __DIR__.'/auth.php';