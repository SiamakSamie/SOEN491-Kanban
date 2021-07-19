<?php

namespace siamaksamie\kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class EmployeeController extends Controller
{

    public function getAllUsers()
    {
        return User::orderBy('name')->get();
    }
}
