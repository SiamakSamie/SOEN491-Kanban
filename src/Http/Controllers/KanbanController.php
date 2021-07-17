<?php

namespace siamaksamie\kanban\Http\Controllers;

use App\Http\Controllers\Controller;

class KanbanController extends Controller
{
    public function getIndex()
    {
        return view('SiamakSamie\Kanban::index');
    }

}
