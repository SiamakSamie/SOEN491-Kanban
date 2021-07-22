<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use SiamakSamie\Kanban\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getAllTasks()
    {
        return Task::all();
    }

    public function getTaskCardsByColumn($id)
    {
        return Task::where('column_id', $id)->with('badge', 'row', 'column')
            ->with(['assignedTo.employee.user' => function ($q) {
                $q->select(['id', 'first_name', 'last_name']);
            }])
            ->with(['erpEmployee' => function ($q) {
                $q->select(['id', 'first_name', 'last_name']);
            }])
            ->with(['reporter' => function ($q) {
                $q->select(['id', 'first_name', 'last_name']);
            }])
            ->with(['erpJobSite' => function ($q) {
                $q->select(['id', 'name']);
            }])->get();
    }

    public function updateTaskCardIndexes(Request $request)
    {
        $taskCards = $request->all();
        $newIndex = 0;
        try {
            foreach ($taskCards as $taskCard) {
                $newIndex++;
                Task::find($taskCard['id'])->update(['index' => $newIndex]);
            }
        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response(['success' => 'true'], 200);
    }

    public function updateTaskCardRowAndColumnId($columnId, $rowId, $taskCardId)
    {
        try {
            Task::find($taskCardId)->update(['column_id' => $columnId, 'row_id' => $rowId]);
        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response(['success' => 'true'], 200);
    }
}
