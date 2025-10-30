<?php

namespace App\Http\Controllers;

use App\Services\RoomScheduleService;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $scheduleService;

    public function __construct(RoomScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Display dashboard dengan data real-time
     */
    public function index()
    {
        // Get all rooms dengan status (untuk initial load)
        $rooms = $this->scheduleService->getAllRoomsStatus();
        
        return view('dashboard', [
            'rooms' => $rooms,
            'totalRooms' => $rooms->count(),
            'occupiedRooms' => $rooms->where('status', 'occupied')->count(),
            'availableRooms' => $rooms->where('status', 'available')->count(),
        ]);
    }
}