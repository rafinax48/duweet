<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function liquidAssetsPerUser(Request $request)
    {
        $rows = DB::table('users as u')
            ->leftJoin('user_accounts as ua', function($join) {
                $join->on('ua.user_id', '=', 'u.id')
                     ->where('ua.is_liquid', '=', 1);
            })
            ->select(
                'u.id as user_id',
                'u.name as user_name',
                DB::raw('COALESCE(SUM(ua.balance), 0) as total_liquid_asset')
            )
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_liquid_asset')
            ->get();

        return response()->json([
            'data' => $rows,
            'meta' => ['count' => $rows->count()]
        ], 200);
    }
}
