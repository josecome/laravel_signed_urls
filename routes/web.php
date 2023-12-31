<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//When access this link the result will be something like:
//http://127.0.0.1:8000/file/1?expires=1695056777&signature=878ccd9fd7e3f89afd3b2848d2fa6453db2c9c5f1a3735e99582fc66f67aab05
Route::get('/download', function () {
    return URL::temporarySignedRoute(
        'file', now()->addMinutes(30), ['id' => 1]
    );
});

//From the link generated above, this route will be called
Route::get('/file/{id}', function (Request $request) {
    if (! $request->hasValidSignature()) {
        abort(401);
    }
    return "Good";

    //If you need to add parameters, you can ignore in validation.
    //But this parameter can be changed
    /*
      if (! $request->hasValidSignatureWhileIgnoring(['year'])) {
        abort(401);
      }
    */
})->name('file');

Route::get('/download_book', function () {
    return URL::temporarySignedRoute(
        'book', now()->addMinutes(30), ['id' => 1]
    );
});

Route::get('/download_video', function () {
    return URL::temporarySignedRoute(
        'video', now()->addMinutes(30), ['id' => 1]
    );
});


//Also is possible to use middleware to validate
Route::post('/book/{id}', function (Request $request) {
    return "Good Book";
})->name('book')->middleware('signed');

//If your signed URLs do not include the domain in the URL hash,
//you should provide the relative argument to the middleware:
Route::post('/video/{id}', function (Request $request) {
    return "Good Video";
})->name('video')->middleware('signed:relative');


