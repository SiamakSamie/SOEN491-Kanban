<?php

namespace siamaksamie\kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use siamaksamie\kanban\Models\Board;

class BoardsController extends Controller
{

    public function getBoards()
    {
        return Board::orderBy('name')->with('members')->get();
    }
}
