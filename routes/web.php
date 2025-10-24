<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/test/liquid-assets', function () {
    $rows = DB::table('users as u')
        ->leftJoin('user_accounts as ua', function($join) {
            $join->on('ua.user_id', '=', 'u.id')
                 ->where('ua.is_liquid', '=', 1);
        })
        ->select('u.id as user_id','u.name as user_name', DB::raw('COALESCE(SUM(ua.balance),0) as total_liquid_asset'))
        ->groupBy('u.id','u.name')
        ->orderByDesc('total_liquid_asset')
        ->get();

    return response()->json($rows);
});



Route::get('/', function () {
    return view('welcome');
});
