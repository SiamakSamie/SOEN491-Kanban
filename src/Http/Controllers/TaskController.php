<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SiamakSamie\Kanban\Models\Badge;
use SiamakSamie\Kanban\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getAllTasks()
    {
        return Task::all();
    }


    public function createTaskCard(Request $request)
    {

        $rules = [
            'taskDescription' => 'required',
            'taskName' => 'required'
        ];

        $customMessages = [
            'taskDescription.required' => 'Description is required',
            'taskName.required' => 'Name is required',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response([
                'success' => 'false',
                'message' => implode(', ', $validator->messages()->all()),
            ], 400);
        }


        $taskCard = $request->all();

        $badge = Badge::firstOrCreate([
            'name' => $request->has('badge') ? $taskCard['badge']['name']: '--',
        ]);

        $maxIndex = Task::where('column_id', $taskCard['selectedColumnId'])->max('index');

        try {
            $maxIndex++;
            $task = Task::create([
                'index' => $maxIndex,
                'reporter_id' => Auth::id(),
                'name' => $taskCard['taskName'],
                'description' => $taskCard['taskDescription'],
                'badge_id' => $badge->id,
                'column_id' => $taskCard['selectedColumnId'],
                'board_id' => $taskCard['boardId'],
                'priority' => $request->input('priority') !== null ? $taskCard['priority']['name'] : null,

            ]);

            if($request->has('assignedTo')){
                $employeeArray = [];
                foreach ($taskCard['assignedTo'] as $employee) {
                    array_push($employeeArray, $employee['id']);
                }
                $task->assignedTo()->sync($employeeArray);
            }



        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response(['success' => 'true'], 200);
    }

    public function getTaskCardsByColumn($id)
    {
        return Task::where('column_id', $id)->with('badge', 'row', 'column')
            ->with(['assignedTo.employee.user' => function ($q) {
                $q->select(['id', 'name']);
            }])
            ->with(['reporter' => function ($q) {
                $q->select(['id', 'name']);
            }])->get();
    }

    public function updateTaskCardsIndexes(Request $request)
    {
        $tasks = $request->all();
        $newIndex = 0;
        try {
            foreach ($tasks as $task) {
                $newIndex++;
                Task::find($task['id'])->update(['index' => $newIndex]);
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
