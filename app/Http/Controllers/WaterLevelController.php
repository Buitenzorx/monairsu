<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaterLevel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WaterLevelController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function getWaterLevel()
    {
        $latestWaterLevel = WaterLevel::latest()->first();

        if (!$latestWaterLevel) {
            return response()->json([
                'level' => null,
                'status' => 'No data available'
            ]);
        }

        $level = $latestWaterLevel->level;

        if ($level < 40) {
            $status = "AMAN";
        } elseif ($level > 40 && $level <= 60) {
            $status = "RAWAN";
        } elseif ($level > 60 && $level <= 80) {
            $status = "KRITIS";
        } else {
            $status = "RUSAK";
        }

        return response()->json([
            'level' => $level,
            'status' => $status
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|numeric'
        ]);

        $waterLevel = new WaterLevel();
        $waterLevel->level = $request->level;
        $waterLevel->save();

        return response()->json([
            'message' => 'Water level recorded successfully',
            'data' => $waterLevel
        ]);
    }

    public function getWaterLevelData()
    {
        $waterLevels = WaterLevel::select('level', DB::raw('DATE_FORMAT(created_at, "%H:%i:%s") as time'))
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get()
            ->sortBy('created_at'); // Sorting back to ascending order for proper display

        return response()->json($waterLevels);
    }

}
