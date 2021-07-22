<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use SiamakSamie\Kanban\Models\Board;
use SiamakSamie\Kanban\Models\Employee;

class KanbanController extends Controller
{
    public function getIndex()
    {
        return view('SiamakSamie\Kanban::index');
    }

    public function getDashboardData()
    {
        $boards = Board::orderBy('name')->with('members')->get();
        $employees = Employee::with('user')->get();


        return [
            'boards' => $boards,
            'employees' => $employees
        ];
    }

    public function getkanbanData($id)
    {
        return Board::with('rows.columns.tasks.badge')
            ->with(['rows.columns.tasks.assignedTo.employee.user' => function ($q) {
                $q->select(['id', 'name']);
            }])
            ->with(['rows.columns.tasks.reporter' => function ($q) {
                $q->select(['id', 'name']);
            }])
            ->with(['members.employee.user' => function ($q) {
                $q->select(['id', 'name']);
            }])
            ->with(['rows.columns.tasks.row', 'rows.columns.tasks.column',])
            ->find($id);
    }

}
