<?php

namespace SiamakSamie\Kanban\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Row extends Model
{
    protected $table = 'kanban_rows';

    protected $guarded = [];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class)->orderBy('index', 'asc');;
    }

    public function columns(): HasMany
    {
        return $this->HasMany(Column::class)->orderBy('index');
    }

}
