<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use SiamakSamie\Kanban\Models\Board;
use SiamakSamie\Kanban\Models\Column;
use SiamakSamie\Kanban\Models\Row;
use SiamakSamie\Kanban\Models\Task;
use Tests\TestCase;

class KanbanDraggingTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testAUserCanChangeTaskIndex()
    {

        Board::create(['name' => 'test board',]);

        Row::create(['board_id' => 1, 'index' => 1, 'name' => 'row 1',]);
        Row::create(['board_id' => 1, 'index' => 1, 'name' => 'row 2',]);

        Column::create(['row_id' => 1, 'index' => 1, 'name' => 'col 1',]);
        Column::create(['row_id' => 1, 'index' => 2, 'name' => 'col 2',]);

        Task::create(['column_id' => 1, 'index' => 1, 'name' => 'task 1', 'description' => 'test']);
        Task::create(['column_id' => 1, 'index' => 2, 'name' => 'task 2', 'description' => 'test']);


        $response = $this->post('kanban/update-task-cards-indexes',
            [
                ['id' => 2, 'index' => 2, 'name' => 'task 2',],
                ['id' => 1, 'index' => 1, 'name' => 'task 1',],
            ]
        );
        // indexes on tasks are swapped to match order of submission

        $this->assertDatabaseHas('kanban_tasks', ['id' => 1, 'index' => 2, 'name' => 'task 1']);
        $this->assertDatabaseHas('kanban_tasks', ['id' => 2, 'index' => 1, 'name' => 'task 2']);

    }

    public function testAUserCanChangeColumnIndex()
    {
        $this->testAUserCanChangeTaskIndex();

        $response = $this->post('kanban/update-column-indexes',
            [
                ['id' => 2, 'index' => 2, 'name' => 'col 1',],
                ['id' => 1, 'index' => 1, 'name' => 'col 2',],
            ]
        );

        // indexes on columns are swapped to match order of submission

        $this->assertDatabaseHas('kanban_columns', ['id' => 1, 'index' => 2, 'name' => 'col 1']);
        $this->assertDatabaseHas('kanban_columns', ['id' => 2, 'index' => 1, 'name' => 'col 2']);
    }

    public function testAUserCanChangeRowIndex()
    {
        $this->testAUserCanChangeTaskIndex();

        $response = $this->post('kanban/update-row-indexes',
            [
                ['id' => 2, 'index' => 2, 'name' => 'row 1',],
                ['id' => 1, 'index' => 1, 'name' => 'row 2',],
            ]
        );

        // indexes on rows are swapped to match order of submission

        $this->assertDatabaseHas('kanban_rows', ['id' => 1, 'index' => 2, 'name' => 'row 1']);
        $this->assertDatabaseHas('kanban_rows', ['id' => 2, 'index' => 1, 'name' => 'row 2']);
    }

}
