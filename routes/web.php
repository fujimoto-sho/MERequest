<?php

// 初回はindexブレードを返す
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');
