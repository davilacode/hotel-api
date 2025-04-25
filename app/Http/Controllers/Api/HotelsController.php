<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotels;
use Illuminate\Http\Request;

class HotelsController extends Controller
{
    /**
     * Muestra todos los hoteles
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $hotels = Hotels::orderBy('id', 'DESC')->get();
        return response()->json($hotels);
    }

    /**
     * Crea un nuevo hotel
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'unique:hotels,name|required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'nit' => 'unique:hotels,nit|required|string|max:255',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $hotel = Hotels::create($request->all());

        if (!$hotel) {
            return response()->json(['message' => 'Error creating hotel'], 500);
        }

        return response()->json($hotel, 201);
    }

    /**
     * Muestra un hotel específico
     * @param int $id - ID del hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $hotel = Hotels::findOrFail($id);

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        $totalRooms = $hotel->rooms()->sum('quantity');

        return response()->json([
            'hotel' => $hotel,
            'total_rooms_created' => $totalRooms
        ]);
    }

    /**
     * Actualiza un hotel específico
     * @param Request $request
     * @param int $id - ID del hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $hotel = Hotels::findOrFail($id);

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'nit' => 'sometimes|required|string|max:255',
            'total_rooms' => 'sometimes|required|integer|min:1',
        ]);

        $hotel->update($request->all());

        if(!$hotel) {
            return response()->json(['message' => 'Error updating hotel'], 500);
        }

        return response()->json($hotel);
    }

    /**
     * Elimina un hotel específico
     * @param int $id - ID del hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $hotel = Hotels::findOrFail($id);

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }

        if ($hotel->rooms()->exists()) {
            return response()->json(['message' => 'Cannot delete hotel with associated rooms'], 400);
        }

        $hotel->delete();

        if (!$hotel) {
            return response()->json(['message' => 'Error deleting hotel'], 500);
        }

        return response()->json(null, 204);
    }
}
