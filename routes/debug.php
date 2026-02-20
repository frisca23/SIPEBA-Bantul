<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/check-users', function () {
    $users = DB::table('users')->select('id', 'username', 'role', 'unit_kerja_id')->get();
    return $users;
});
