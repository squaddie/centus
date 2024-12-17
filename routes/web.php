<?php

use App\View\Pages\ManageChannels;
use App\View\Pages\ManageCities;
use App\View\Pages\ManageHistory;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'),])
    ->group(function () {
        Route::get('/', ManageHistory::class)->name('history');
        Route::get('/manage-cities', ManageCities::class)->name('manage-cities');
        Route::get('/manage-channels', ManageChannels::class)->name('manage-channels');
    });
