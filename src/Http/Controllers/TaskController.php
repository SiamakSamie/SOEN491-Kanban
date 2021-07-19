<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use SiamakSamie\Kanban\Models\Task;


class TaskController extends Controller
{
    public function getAllTasks()
    {
        return Task::all();
    }
}
