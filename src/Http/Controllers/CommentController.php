<?php

namespace siamaksamie\kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use siamaksamie\kanban\Models\Comment;

class CommentController extends Controller
{
    public function getAllComments()
    {
        return Comment::all();
    }

}
