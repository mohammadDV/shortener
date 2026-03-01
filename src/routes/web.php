<?php

use Illuminate\Support\Facades\Route;
use Mohammad\Shortener\Http\Controllers\RedirectController;

Route::get('/{code}', RedirectController::class);