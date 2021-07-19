<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use SiamakSamie\Kanban\Models\Board;

class BoardsController extends Controller
{

    public function getBoards()
    {
        return Board::orderBy('name')->with('members')->get();
    }
}
