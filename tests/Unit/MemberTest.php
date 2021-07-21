<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use SiamakSamie\Kanban\Models\Board;
use SiamakSamie\Kanban\Models\Employee;
use SiamakSamie\Kanban\Models\Member;
use Tests\TestCase;

class MemberTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function testAUserCanCreateSeveralMembers()
    {
        $this->seed();

        Board::create(['name' => 'test board',]);
        Employee::create(['user_id' => '1', 'role' => 'admin']);
        Employee::create(['user_id' => '2', 'role' => 'admin']);


        $response = $this->post('kanban/create-members/1', [
            ['id' => 1,],
            ['id' => 2,],
        ]);
        $this->assertCount(2, Member::all());
    }


    public function testAUserCanDeleteAMember()
    {
        $this->testAUserCanCreateSeveralMembers();
        $this->assertCount(2, Member::all());
        $response = $this->post('kanban/delete-member/1');
        $this->assertCount(1, Member::all());
    }
}
