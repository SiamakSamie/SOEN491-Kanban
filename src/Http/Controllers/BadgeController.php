<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use SiamakSamie\Kanban\Models\Badge;

class BadgeController extends Controller
{

    public function getAllBadges()
    {
        return Badge::orderBy('name')->get();
    }
}
