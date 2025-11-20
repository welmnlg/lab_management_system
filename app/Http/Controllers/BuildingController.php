<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller
{
    /**
     * Get all buildings
     */
    public function index()
    {
        try {
            $buildings = Building::with('rooms')->get();

            return response()->json([
                'success' => true,
                'data' => $buildings
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting buildings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data gedung'
            ], 500);
        }
    }

    /**
     * Store new building
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'building_name' => 'required|string|max:255',
                'building_code' => 'required|string|max:10|unique:buildings,building_code'
            ]);

            $building = Building::create([
                'building_name' => $request->building_name,
                'building_code' => strtoupper($request->building_code)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gedung berhasil ditambahkan',
                'data' => $building
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan gedung: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms for specific building
     */
    public function getRooms($buildingId)
    {
        try {
            $rooms = Room::where('building_id', $buildingId)->get();

            return response()->json([
                'success' => true,
                'data' => $rooms
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting rooms: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data ruangan'
            ], 500);
        }
    }

    /**
     * Get rooms by building code (NEW METHOD)
     */
    public function getRoomsByBuildingCode($buildingCode)
    {
        try {
            $building = Building::where('building_code', $buildingCode)->first();
            
            if (!$building) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gedung tidak ditemukan'
                ], 404);
            }
            
            $rooms = Room::where('building_id', $building->building_id)->get();
            
            return response()->json([
                'success' => true,
                'data' => $rooms
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting rooms by building code: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}