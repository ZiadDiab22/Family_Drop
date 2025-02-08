<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function editCommission(Request $request)
    {
        $request->validate([
            'value' => 'required'
        ]);

        if (Auth::user()->type_id == 2) return response()->json(['status' => false, 'error' => 'Unauthorized'], 401);
        if (DB::table('settings')->count() === 0) {
            DB::table('settings')->insert([
                'name' => 'Marketer Commission',
                'value' => $request->value
            ]);
        } else {
            DB::table('settings')
                ->where('id', DB::table('settings')->value('id'))
                ->update(['value' => $request->value]);
        }

        $data = Setting::get();

        return response([
            'status' => true,
            'settings' => $data
        ]);
    }

    public function showSettings()
    {

        $data = Setting::get();

        return response([
            'status' => true,
            'settings' => $data
        ]);
    }
}
