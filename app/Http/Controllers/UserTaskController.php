<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserTaskStoreRequest;
use App\Http\Requests\UserTaskUpdateRequest;
use App\Http\Resources\UserTaskCollection;
use App\Http\Resources\UserTaskResource;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserTaskController extends Controller
{
    public function index(Request $request)
    {
        $userTasks = UserTask::with(['user', 'task'])->paginate();

        return response()->json($userTasks);
    }

    public function store(UserTaskStoreRequest $request)
    {
        $userTask = UserTask::create($request->validated());

        return response()->json($userTask);
    }

    public function show(Request $request, UserTask $userTask)
    {
        return response()->json($userTask->load(['user', 'task']));
    }

    public function update(UserTaskUpdateRequest $request, UserTask $userTask)
    {
        $userTask->update($request->validated());

        return response()->json($userTask);
    }

    public function destroy(Request $request, UserTask $userTask)
    {
        $userTask->delete();

        return response()->json($userTask);
    }
}
