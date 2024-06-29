<?php 

namespace AlphansoTech\LiveStreaming\Controllers;
use Illuminate\Support\Facades\Route;









Route::get('alphanso/home/page', [LiveStreamingController::class,'HomePage']);

Route::get('live/stream/stop/{stream_id}/{wowza_id}', [LiveStreamingController::class,'StopLiveStream'])->name('stop.live.stream');



