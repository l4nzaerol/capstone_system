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

    public function analytics(Request $request) {
        $query = Production::query();
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();

        $kpis = [
            'total' => $data->count(),
            'in_progress' => $data->where('status', 'In Progress')->count(),
            'completed' => $data->where('status', 'Completed')->count(),
            'hold' => $data->where('status', 'Hold')->count(),
        ];

        $daily = $data->groupBy(function ($p) { return optional($p->date)->format('Y-m-d'); })
            ->map(function ($items, $date) {
                return [
                    'date' => $date,
                    'quantity' => $items->sum('quantity'),
                ];
            })
            ->values()
            ->sortBy('date')
            ->values();

        $stages = ['Preparation', 'Assembly', 'Finishing', 'Quality Control'];
        $stageBreakdown = collect($stages)->map(function ($stage) use ($data) {
            return [
                'name' => $stage,
                'value' => $data->where('stage', $stage)->count(),
            ];
        })->values();

        return response()->json([
            'kpis' => $kpis,
            'daily_output' => $daily,
            'stage_breakdown' => $stageBreakdown,
        ]);
    }
}
