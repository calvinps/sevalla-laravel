<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

function cpuUsage(int $sec): void {
    $start = microtime(true);
    while ((microtime(true) - $start) < $sec) {
        sqrt(rand());
    }
}

function memUsage(int $mb): void {
    $allocated= [];
    for ($i = 0; $i < $mb; $i++) {
        $allocated[] = str_repeat('A', 1024 * 1024);
    }
    defer(function(){
      sleep(2);
      unset($allocated);
    });
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cpu', function (Request $request) {
  defer(function(){
    $sec = (int) $request->query('sec', 2);
    cpuUsage($sec);
  });

  return response()->json(['message' => 'CPU']);
});

Route::get('/mem', function (Request $request) {
  $mb = (int) $request->query('mb', 50);
  memUsage($mb);

  return response()->json(['message' => "Memory"]);
});
