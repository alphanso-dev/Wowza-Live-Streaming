<?php 

namespace AlphansoTech\LiveStreaming\Controllers;
use Illuminate\Support\Facades\Route;









Route::get('alphanso/home/page', [LiveStreamingController::class,'HomePage']);