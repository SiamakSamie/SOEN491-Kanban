<?php

namespace siamaksamie\kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use siamaksamie\kanban\Models\Badge;

class BadgeController extends Controller
{

    public function getAllBadges()
    {
        return Badge::orderBy('name')->get();
    }
}
