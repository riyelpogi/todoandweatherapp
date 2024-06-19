<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/weather', function(Request $request) {
    $apiKey = config('services.darksky.apiKey');

    $validated = $request->validate([
        'arg' => 'required'
    ]);
    $response = Http::get("https://api.tomorrow.io/v4/weather/forecast?location=".$validated['arg']."&apikey=$apiKey");

    return $response;
});

Route::get('/places', function(Request $request) {
    $response = Http::get("https://api.distancematrix.ai/maps/api/geocode/json?address=$request->address&key=joM7gS3yRtLCclyKmXsjOBWHu9kyjJNg6fmMIU0BtXUMHytxAiC6LeDkAqvEOV73");

    return $response;
});
