<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use SiamakSamie\Kanban\Models\Comment;

class CommentController extends Controller
{
    public function getAllComments()
    {
        return Comment::all();
    }

}
