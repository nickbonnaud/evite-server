<?php

use App\Http\Controllers\Inviter\AuthController;
use App\Http\Controllers\Invitee\CalendarLinksController;
use App\Http\Controllers\Invitee\RsvpController as InviteeRsvpController;
use App\Http\Controllers\Inviter\RsvpController as InviterRsvpController;
use Illuminate\Support\Facades\Route;

Route::prefix('inviter')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('login', 'login');
        Route::get('refresh', 'refresh')->middleware(['auth:sanctum']);
    });

    Route::middleware('auth:sanctum')->group(function() {
        Route::controller(InviterRsvpController::class)->group(function() {
            Route::get('rsvp', 'index');
            Route::post('rsvp', 'post');
        });
    });
});

Route::prefix('invitee')->group(function() {
    Route::controller(InviteeRsvpController::class)->group(function() {
        Route::get('rsvp', 'index');
        Route::patch('rsvp/{rsvp}', 'update');
    });

    Route::controller(CalendarLinksController::class)->group(function() {
        Route::get('calendar', 'index');
    });
});