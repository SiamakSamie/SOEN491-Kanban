<?php

namespace SiamakSamie\Kanban\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property \Illuminate\Support\Carbon|null $deadline
 */

class Task extends Model
{
    protected $table = 'kanban_tasks';

    protected $guarded = [];


    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    public function row(): BelongsTo
    {
        return $this->belongsTo(Row::class);
    }

    public function assignedTo(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'kanban_employee_task', 'task_id', 'employee_id');
    }

    public function comments(): HasMany
    {
        return $this->HasMany(Comment::class);
    }
}
