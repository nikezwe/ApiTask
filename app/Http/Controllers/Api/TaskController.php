<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // ✅ Liste des tâches de l'utilisateur connecté
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->latest()->get();
        return response()->json($tasks);
    }

    // ✅ Créer une tâche
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date'
        ]);

        $validated['user_id'] = auth()->id();

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    // ✅ Afficher une tâche
    public function show($id)
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return response()->json($task);
    }

    // ✅ Modifier une tâche
    public function update(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date'
        ]);

        $task->update($validated);

        return response()->json($task);
    }


// Supprimer une tâche
public function destroy(Task $task)
{
    // Vérifier que la tâche appartient bien à l’utilisateur connecté
    if ($task->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $task->delete();

    return response()->json(['message' => 'Deleted successfully']);
}


}
