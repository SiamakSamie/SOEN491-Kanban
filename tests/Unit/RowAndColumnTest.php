<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use SiamakSamie\Kanban\Models\Board;
use SiamakSamie\Kanban\Models\Column;
use SiamakSamie\Kanban\Models\Row;
use Tests\TestCase;

class RowAndColumnTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testAUserCanCreateARowWithColumns()
    {
        $this->seed();

        Board::create(['name' => 'test board',]);

        $response = $this->post('kanban/save-row-and-columns', [
            'boardId' => 1,
            'name' => 'test row',
            'rowIndex' => 1,
            'rowId' => null,
            'columns' => [
                ['id' => null, 'index' => null, 'name' => 'test column 1'], ['id' => null, 'index' => null, 'name' => 'test column 2'],
            ]

        ]);
        $this->assertCount(1, Row::all());
        $this->assertCount(2, Column::all());

    }

    public function testAUserCanEditARowWithColumns()
    {
        $this->testAUserCanCreateARowWithColumns();
        $this->assertDatabaseHas('kanban_columns', ['name' => 'test column 1']);
        $this->assertDatabaseHas('kanban_rows', ['name' => 'test row']);

        $response = $this->post('kanban/save-row-and-columns', [
            'boardId' => 1,
            'name' => 'edited test row',
            'rowIndex' => 1,
            'rowId' => 1,
            'columns' => [
                ['id' => 1, 'index' => 1, 'name' => 'edited test column 1'],
            ]
        ]);

        $this->assertDatabaseHas('kanban_rows', ['name' => 'edited test row']);
        $this->assertDatabaseMissing('kanban_rows', ['name' => 'test row']);

        $this->assertDatabaseHas('kanban_columns', ['name' => 'edited test column 1']);
        $this->assertDatabaseMissing('kanban_columns', ['name' => 'test column 1']);

        // leftover columns should also be deleted
        $this->assertDatabaseMissing('kanban_columns', ['name' => 'test column 2']);
    }

    public function testAUserCanDeleteARowWithColumns()
    {
        $this->testAUserCanCreateARowWithColumns();
        $this->assertCount(1, Row::all());
        $this->assertCount(2, Column::all());
        $response = $this->post('kanban/delete-row/1');
        $this->assertCount(0, Row::all());
        $this->assertCount(0, Column::all());           }
}
