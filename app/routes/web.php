<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationTemplateController;
use App\Http\Controllers\QRVerificationController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\BusTrackingController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// Reservation Routes
Route::get('/reservations/schedule', [ReservationController::class, 'schedule'])->name('reservations.schedule');
Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/success', [ReservationController::class, 'success'])->name('reservations.success');
Route::get('/reservations/my-reservations', [ReservationController::class, 'myReservations'])->middleware('auth')->name('reservations.my');
Route::get('/reservations/booked-slots', [ReservationController::class, 'getBookedSlots'])->name('reservations.booked-slots');
Route::get('/reservations/template/download', [ReservationTemplateController::class, 'download'])->name('reservations.template.download');
// QR Code Verification
Route::get('/verify/{id}', [QRVerificationController::class, 'verify'])->name('qr.verify');
Route::get('/scan', [QRVerificationController::class, 'scan'])->name('qr.scan');
// JSON endpoints replacing legacy get_reservations.php and get_reservation_details.php
Route::get('/reservations/by-date', [ReservationController::class, 'byDate'])->name('reservations.byDate');
Route::get('/reservations/{reservation}/json', [ReservationController::class, 'detailsJson'])->name('reservations.detailsJson');

// Reviews (public)
Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Public schedule
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

// Bus Tracking
Route::get('/bus-tracking', [BusTrackingController::class, 'index'])->name('bus.tracking');
Route::get('/api/bus-tracking/status', [BusTrackingController::class, 'getCurrentStatus'])->name('bus.tracking.status');

// News & Agenda (Aktivitas)
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/agenda', [NewsController::class, 'agenda'])->name('news.agenda');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

// Static pages
Route::get('/e-resources', [\App\Http\Controllers\StaticPageController::class, 'eResources'])->name('static.eresources');
Route::get('/library-location', [\App\Http\Controllers\StaticPageController::class, 'libraryLocation'])->name('static.location');
Route::get('/literacy-agenda', [\App\Http\Controllers\StaticPageController::class, 'literacyAgenda'])->name('static.literacy');
Route::get('/literacy/{id}', [\App\Http\Controllers\StaticPageController::class, 'literacyDetail'])->name('static.literacy.detail');
Route::get('/info', [\App\Http\Controllers\StaticPageController::class, 'info'])->name('static.info');

// Auth routes (from Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('books', AdminBookController::class);
    // Reservations admin
    Route::get('reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::post('reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
    // Reviews admin
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    // Users admin
    Route::resource('users', AdminUserController::class)->except(['show']);
    // Schedule admin
    Route::get('schedule', [AdminScheduleController::class, 'index'])->name('schedule.index');
    Route::get('schedule/preview', [AdminScheduleController::class, 'preview'])->name('schedule.preview');
    Route::post('schedule/commit', [AdminScheduleController::class, 'commit'])->name('schedule.commit');
    
    // Bus Tracking Admin
    Route::get('bus-tracking', [BusTrackingController::class, 'adminControl'])->name('bus.control');
    Route::post('bus-tracking/update', [BusTrackingController::class, 'updateStatus'])->name('bus.updateStatus');
    
    // News Admin
    Route::resource('news', AdminNewsController::class);
});

require __DIR__.'/auth.php';
