<?php

use App\Http\Controllers\CustomLfmController;
use Illuminate\Support\Facades\Route;

Route::controller(CustomLfmController::class)->group(function () {
    Route::get('/', 'show')->name('unisharp.lfm.show');
    Route::get('/jsonitems', 'getItems')->name('unisharp.lfm.getItems');
    Route::post('/upload', 'upload')->name('unisharp.lfm.upload');
    Route::get('/folders', 'getFolders')->name('unisharp.lfm.getFolders');
    Route::get('/newfolder', 'getAddfolder')->name('unisharp.lfm.getAddfolder');
    Route::post('/rename', 'getRename')->name('unisharp.lfm.getRename');
    Route::get('/move', 'getMove')->name('unisharp.lfm.getMove');
    Route::post('/resize', 'performResize')->name('unisharp.lfm.resize');
    Route::get('/download', 'getDownload')->name('unisharp.lfm.getDownload');
    Route::post('/delete', 'getDelete')->name('unisharp.lfm.getDelete');
    Route::get('/errors', 'getErrors')->name('unisharp.lfm.getErrors');
});