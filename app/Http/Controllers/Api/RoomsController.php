<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotels;
use App\Models\Rooms;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    /**
     * Muestra todas las acomodaciones creadas de todos los hoteles
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $rooms = Rooms::with([
            'hotel' => function ($query) {
                $query->select('id', 'name');
            }
        ])->get();
        return response()->json($rooms);
    }

    /**
     * Muestra todas las acomodaciones creadas de un hotel
     * @param int $hotelId - ID del hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($hotelId)
    {
        $rooms = Rooms::where('hotel_id', $hotelId)->get();

        return response()->json($rooms);
    }

    /**
     * Crea una nueva acomodación
     * @param Request $request
     * @param int $hotelId - ID del hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $hotelId)
    {

        $hotel = Hotels::find($hotelId);
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        $request->validate([
            'type' => 'required|in:standard,junior,suite',
            'accommodation' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $type = $request->type;
                    $validCombinations = [
                        'standard' => ['single', 'double'],
                        'junior' => ['triple', 'quadruple'],
                        'suite' => ['single', 'double', 'triple'],
                    ];

                    if (!isset($validCombinations[$type]) || !in_array($value, $validCombinations[$type])) {
                        $fail("The $attribute is invalid for the selected type.");
                    }
                },
            ],
            'quantity' => 'required|integer|min:1',
        ]);

        $roomExists = Rooms::where('hotel_id', $hotelId)
            ->where('type', $request->type)
            ->where('accommodation', $request->accommodation)
            ->exists();

        if ($roomExists) {
            return response()->json(['message' => 'Room type already exists'], 422);
        }

        $totalRooms = Rooms::where('hotel_id', $hotelId)->sum('quantity');

        if ($totalRooms + $request->quantity > $hotel->total_rooms) {
            return response()->json(['message' => 'Total rooms exceed hotel capacity'], 422);
        }


        $room = new Rooms($request->all());
        $room->hotel_id = $hotelId;
        $room->save();

        if (!$room) {
            return response()->json(['message' => 'Room not created'], 500);
        }

        return response()->json($room, 201);

    }

    /**
     * Muestra una acomodación específica
     * @param int $hotelId - ID del hotel
     * @param int $id - ID de la acomodación
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($hotelId, $id)
    {
        $room = Rooms::where('hotel_id', $hotelId)->find($id);

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        return response()->json($room);

    }

    /**
     * Actualiza una acomodación específica
     * @param Request $request
     * @param int $hotelId - ID del hotel
     * @param int $id - ID de la acomodación
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $hotelId, $id)
    {
        $hotel = Hotels::find($hotelId);
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        $room = Rooms::where('hotel_id', $hotelId)->find($id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        $request->validate([
            'type' => 'sometimes|required|in:standard,junior,suite',
            'accommodation' => [
                'sometimes',
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $type = $request->type;
                    $validCombinations = [
                        'standard' => ['single', 'double'],
                        'junior' => ['triple', 'quadruple'],
                        'suite' => ['single', 'double', 'triple'],
                    ];

                    if (!isset($validCombinations[$type]) || !in_array($value, $validCombinations[$type])) {
                        $fail("The $attribute is invalid for the selected type.");
                    }
                },
            ],
            'quantity' => 'sometimes|required|integer|min:1',
        ]);

        $roomExists = Rooms::where('hotel_id', $hotelId)
            ->where('id', '!=', $id)
            ->where('type', $request->type)
            ->where('accommodation', $request->accommodation)
            ->exists();
        if ($roomExists) {
            return response()->json(['message' => 'Room type already exists'], 422);
        }

        $totalRooms = Rooms::where('hotel_id', $hotelId)->sum('quantity');
        if ($totalRooms + ($request->quantity - $room->quantity) > $hotel->total_rooms) {
            return response()->json(['message' => 'Total rooms exceed hotel capacity'], 422);
        }

        $room->update($request->all());
        if (!$room) {
            return response()->json(['message' => 'Room not updated'], 500);
        }

        return response()->json($room);

    }

    /**
     * Elimina una acomodación específica
     * @param int $hotelId - ID del hotel
     * @param int $id - ID de la acomodación
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($hotelId, $id)
    {
        $room = Rooms::where('hotel_id', $hotelId)->find($id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        $room->delete();

        if (!$room) {
            return response()->json(['message' => 'Room not deleted'], 500);
        }

        return response()->json(['message' => 'Room deleted successfully']);

    }
}
