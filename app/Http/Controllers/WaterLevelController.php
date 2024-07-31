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

    public function history(Request $request)
{
    $query = WaterLevel::orderBy('created_at', 'desc');

    // Apply filters if provided
    if ($request->has('date') && $request->input('date')) {
        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $query->whereDate('created_at', $date);
    }

    if ($request->has('start_time') && $request->has('end_time') && $request->input('start_time') && $request->input('end_time')) {
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        
        // Ensure the timezone is applied to the filter
        $query->whereBetween('created_at', [
            Carbon::parse($date . ' ' . $startTime)->timezone('Asia/Jakarta')->startOfDay(),
            Carbon::parse($date . ' ' . $endTime)->timezone('Asia/Jakarta')->endOfDay()
        ]);
    }

    // Get all levels with filter applied
    $waterLevels = $query->get();
    $displayedLevels = $waterLevels->take(10);

    // Transform data for display
    $displayedLevels->transform(function ($waterLevel, $key) {
        $waterLevel->no = $key + 1;
        $waterLevel->tanggal = Carbon::parse($waterLevel->created_at)->timezone('Asia/Jakarta')->format('Y-m-d');
        $waterLevel->waktu = Carbon::parse($waterLevel->created_at)->timezone('Asia/Jakarta')->format('H:i:s');

        if ($waterLevel->level < 40) {
            $waterLevel->status = "AMAN";
        } elseif ($waterLevel->level > 40 && $waterLevel->level <= 60) {
            $waterLevel->status = "RAWAN";
        } elseif ($waterLevel->level > 60 && $waterLevel->level <= 80) {
            $waterLevel->status = "KRITIS";
        } else {
            $waterLevel->status = "RUSAK";
        }

        return $waterLevel;
    });

    return view('history', [
        'displayedLevels' => $displayedLevels,
        'allLevels' => $waterLevels
    ]);
}


}