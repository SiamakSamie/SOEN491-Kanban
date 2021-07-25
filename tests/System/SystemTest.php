<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SiamakSamie\Kanban\Models\Badge;
use SiamakSamie\Kanban\Models\Board;
use SiamakSamie\Kanban\Models\Column;
use SiamakSamie\Kanban\Models\Employee;
use SiamakSamie\Kanban\Models\Member;
use SiamakSamie\Kanban\Models\Row;
use SiamakSamie\Kanban\Models\Task;
use Tests\TestCase;

class SystemTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {

        $this->seed();

        // verifying that the extension exists

        $response = $this->get('/kanban');
        $response->assertStatus(200);

        //creating a new board

        $this->assertCount(0, Board::all());

        $this->post('kanban/create-board', [
            'name' => 'test board'
        ]);

        // verifying that the new board was created

        $this->assertCount(1, Board::all());
        $this->assertDatabaseHas('kanban_boards', ['name' => 'test board']);

        // verifying that the extension to the new board exists

        $response = $this->get('/kanban/board?id=1');
        $response->assertStatus(200);

        //creating and testing 2 new employees

        $this->post('kanban/create-kanban-employees', [
            'selectedUsers' =>
                [
                    ['id' => 1,],
                    ['id' => 2,],
                    ['id' => 3,],
                ],
            'role' => "employee"
        ]);

        $this->assertCount(3, Employee::all());

        // creating members in the new board with the new employees

        $this->post('kanban/create-members/1', [
            ['id' => 1,],
            ['id' => 2,],
            ['id' => 3,],
        ]);

        $this->assertCount(3, Member::all());

        //creating new rows and columns in the new board

        $this->post('kanban/save-row-and-columns', [
            'boardId' => 1,
            'name' => 'test row',
            'rowIndex' => 1,
            'rowId' => null,
            'columns' => [
                ['id' => null, 'index' => null, 'name' => 'test column 1'], ['id' => null, 'index' => null, 'name' => 'test column 2'],
            ]

        ]);
        $this->assertCount(1, Row::all());

        // Created a new task in the new row with a badge and assigned users from the kanban

        $this->assertCount(0, Badge::all());

        $this->post('kanban/create-task', [
            'taskName' => 'test task',
            'index' => 1,
            'taskDescription' => 'desc',
            'selectedColumnId' => 1,
            'selectedRowId' => 1,
            'boardId' => 1,
            'priority' => null,
            'badge' => ['name' => 'test badge'],
            'assignedTo' => [['id' => '1'], ['id' => '2']]
        ]);

        $this->assertDatabaseHas('kanban_tasks', ['name' => 'test task']);
        $this->assertDatabaseHas('kanban_badges', ['name' => 'test badge']);
        $this->assertDatabaseHas('kanban_employee_task', ['employee_id' => 1]);
        $this->assertDatabaseHas('kanban_employee_task', ['employee_id' => 2]);

        // testing cascading delete

        $this->post('kanban/delete-board/1');
        $this->assertCount(0, Board::all());
        $this->assertCount(0, Row::all());
        $this->assertCount(0, Column::all());
        $this->assertCount(0, Task::all());
        $this->assertCount(0, Member::all());
    }
}
