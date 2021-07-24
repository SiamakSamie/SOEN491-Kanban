<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Support\Facades\Auth;
use SiamakSamie\Kanban\Models\Badge;
use SiamakSamie\Kanban\Models\Board;
use SiamakSamie\Kanban\Models\Column;
use SiamakSamie\Kanban\Models\Employee;
use SiamakSamie\Kanban\Models\Row;
use SiamakSamie\Kanban\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->seed();

        Board::create(['name' => 'test board',]);
        Employee::create(['user_id' => '1', 'role' => 'admin']);
        Employee::create(['user_id' => '2', 'role' => 'admin']);
        Row::create(['board_id' => 1, 'index' => 1, 'name' => 'row',]);
        Column::create(['row_id' => 1, 'index' => 1, 'name' => 'col',]);
    }

    public function testAUserCanCreateATask()
    {

        $this->assertCount(0, Task::all());

        $response = $this->post('kanban/create-task', [
            'taskName' => 'test task',
            'index' => 1,
            'taskDescription' => 'desc',
            'selectedColumnId' => 1,
            'selectedRowId' => 1,
            'boardId' => 1,
            'priority' => null,
        ]);

        $this->assertCount(1, Task::all());
        $this->assertDatabaseHas('kanban_tasks', ['name' => 'test task']);
    }

    public function testAUserCanCreateATaskWithNewBadge()
    {
        $this->assertCount(0, Badge::all());

        $response = $this->post('kanban/create-task', [
            'taskName' => 'test board',
            'index' => 1,
            'taskDescription' => 'desc',
            'selectedColumnId' => 1,
            'selectedRowId' => 1,
            'boardId' => 1,
            'priority' => null,
            'badge' => ['name' => 'test badge']
        ]);

        $this->assertCount(1, Badge::all());
        $this->assertDatabaseHas('kanban_badges', ['name' => 'test badge']);
    }

    public function testAUserCanCreateATaskWithAssignedEmployees()
    {
        $this->assertCount(0, Task::all());

        $response = $this->post('kanban/create-task', [
            'taskName' => 'test board',
            'index' => 1,
            'taskDescription' => 'desc',
            'selectedColumnId' => 1,
            'selectedRowId' => 1,
            'boardId' => 1,
            'priority' => null,
            'assignedTo' => [['id' => '1'], ['id' => '2']]
        ]);

        $this->assertDatabaseHas('kanban_employee_task', ['employee_id' => 1]);
        $this->assertDatabaseHas('kanban_employee_task', ['employee_id' => 2]);
    }

}
