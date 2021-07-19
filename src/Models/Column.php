<?php

namespace siamaksamie\kanban\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Column extends Model
{
    protected $table = 'kanban_columns';

    protected $guarded = [];

    public function row(): BelongsTo
    {
        return $this->belongsTo(Row::class)->orderBy('index', 'asc');;
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('index', 'asc');
    }
}
