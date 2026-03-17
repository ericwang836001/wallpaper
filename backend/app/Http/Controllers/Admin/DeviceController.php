<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::orderBy('type')
            ->orderBy('brand')
            ->orderBy('name')
            ->paginate($request->get('per_page', 20));

        return response()->json(['code' => 200, 'data' => $devices]);
    }

    public function toggleActive(Device $device)
    {
        $device->update(['is_active' => !$device->is_active]);
        return response()->json(['code' => 200, 'message' => '状态已切换', 'data' => $device]);
    }
}
