<?php

namespace siamaksamie\kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use siamaksamie\kanban\Models\Task;


class TaskController extends Controller
{
    public function getAllTasks()
    {
        return Task::all();
    }
}
