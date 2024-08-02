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

        $status = $this->getLevelStatus($level);

        return response()->json([
            'level' => $level,
            'status' => $status
        ]);
    }

    public function store(Request $request)
    {
        \Log::info('Request Data: ', $request->all());

        $request->validate([
            'level' => 'required|numeric'
        ]);

        $waterLevel = new WaterLevel();
        $waterLevel->level = $request->level;
        $waterLevel->created_at = Carbon::now('Asia/Jakarta'); // Store time in WIB
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

    public function history(Request $request)
    {
        $query = WaterLevel::orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->has('date') && $request->input('date')) {
            $date = Carbon::parse($request->input('date'))->format('Y-m-d');
            $query->whereDate('created_at', $date);
        }

        if ($request->has('start_time') && $request->input('start_time')) {
            $startTime = Carbon::parse($request->input('start_time'))->format('H:i:s');
            $query->whereTime('created_at', '>=', $startTime);
        }

        if ($request->has('end_time') && $request->input('end_time')) {
            $endTime = Carbon::parse($request->input('end_time'))->format('H:i:s');
            $query->whereTime('created_at', '<=', $endTime);
        }

        if ($request->has('time') && $request->input('time')) {
            $time = $request->input('time');
            $query->whereTime('created_at', '=', $time);
        }

        $allLevels = $query->get();

        // Transform all levels to include additional fields
        $allLevels->transform(function ($waterLevel, $key) {
            $waterLevel->no = $key + 1;
            $waterLevel->tanggal = Carbon::parse($waterLevel->created_at)->format('Y-m-d');
            $waterLevel->waktu = Carbon::parse($waterLevel->created_at)->timezone('Asia/Jakarta')->format('H:i:s');
            $waterLevel->status = $this->getLevelStatus($waterLevel->level);
            return $waterLevel;
        });

        // Take first 10 for display (latest first)
        $displayedLevels = $allLevels->take(10);

        return view('history', [
            'displayedLevels' => $displayedLevels,
            'allLevels' => $allLevels
        ]);
    }

    private function getLevelStatus($level)
    {
        $maxHeight = 84; // Tinggi maksimum sumur dalam meter
    
        if ($level < 0.40 * $maxHeight) {
            return "AMAN"; // H < 33.6 meter
        } elseif ($level < 0.60 * $maxHeight) {
            return "RAWAN"; // 33.6 ≤ H < 50.4 meter
        } elseif ($level < 0.80 * $maxHeight) {
            return "KRITIS"; // 50.4 ≤ H < 67.2 meter
        } else {
            return "RUSAK"; // H ≥ 67.2 meter
        }
    }
}