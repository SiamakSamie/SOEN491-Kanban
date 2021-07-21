<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use SiamakSamie\Kanban\Models\Board;
use Tests\TestCase;

class BoardTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function testAUserCanCreateABoard()
    {
        $this->assertCount(0, Board::all());

        $response = $this->post('kanban/create-board', [
            'name' => 'test board'
        ]);

        $this->assertCount(1, Board::all());
        $this->assertDatabaseHas('kanban_boards', ['name' => 'test board']);
    }

    public function testAUserCanEditABoard()
    {
        $this->testAUserCanCreateABoard();
        $this->assertCount(1, Board::all());
        $this->assertDatabaseHas('kanban_boards', ['name' => 'test board']);


        $response = $this->post('kanban/create-board', [
            'id' => 1,
            'name' => 'edited Board'
        ]);
        $this->assertCount(1, Board::all());
        $this->assertDatabaseHas('kanban_boards', ['name' => 'edited Board']);
        $this->assertDatabaseMissing('kanban_boards', ['name' => 'test board']);
    }

    public function testAUserCanDeleteABoard()
    {
        $this->testAUserCanCreateABoard();
        $this->assertCount(1, Board::all());
        $response = $this->post('kanban/delete-board/1');
        $this->assertCount(0, Board::all());
    }
}
