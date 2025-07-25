<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class TaskController extends Controller
{
    public function index(): JsonResponse
    {   
       try {
        //code...
         $tasks = auth()->user()->tasks()->latest()->get();
        return response()->json([
            'success'=>true,
            'message'=>'',
            'data'=>$tasks,
            'errors'=>null,
         ]);
       } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            'success'=>true,
            'message'=>"An error occurred while fetching tasks",
            'data'=>null,
            'errors'=>$th->getMessage(),
         ],500);
       }
    }
    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date'
        ]);

        $task = auth()->user()->tasks()->create($validated);
        
        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        // Vérifier que la tâche appartient à l'utilisateur authentifié
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($task);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date'
        ]);

        $task->update($validated);
        
        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();
        
        return response()->json(['message' => 'Task deleted successfully']);
    }
}