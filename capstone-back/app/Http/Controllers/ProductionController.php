<?php

// app/Http/Controllers/ProductionController.php
namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;
use App\Events\ProductionUpdated;

class ProductionController extends Controller
{
    public function index(Request $request) {
        // optional filters from query string
        $query = Production::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return response()->json($query->orderBy('date', 'desc')->get());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'date' => 'required|date',
            'stage' => 'required|string|in:Preparation,Assembly,Finishing,Quality Control',
            'status' => 'required|string|in:Pending,In Progress,Completed,Hold',
            'quantity' => 'required|integer|min:0',
            'resources_used' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $production = Production::create($data);

        // Broadcast real-time update
        broadcast(new ProductionUpdated($production))->toOthers();

        return response()->json($production, 201);
    }

    public function show($id) {
        return Production::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $production = Production::findOrFail($id);

        $data = $request->validate([
            'product_name' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'stage' => 'sometimes|string|in:Preparation,Assembly,Finishing,Quality Control',
            'status' => 'sometimes|string|in:Pending,In Progress,Completed,Hold',
            'quantity' => 'sometimes|integer|min:0',
            'resources_used' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $production->update($data);

        broadcast(new ProductionUpdated($production))->toOthers();

        return response()->json($production);
    }

    public function destroy($id) {
        $production = Production::findOrFail($id);
        $production->delete();

        return response()->json(['message' => 'Production deleted']);
    }
}
