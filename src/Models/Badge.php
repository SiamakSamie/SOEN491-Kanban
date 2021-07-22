<?php

namespace SiamakSamie\Kanban\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Badge extends Model
{
    protected $table = 'kanban_badges';

    protected $guarded = [];

    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }
}
