<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use SiamakSamie\Kanban\Models\Employee;
use Tests\TestCase;

class EmployeeTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function testAUserCanCreateSeveralEmployees()
    {
        $this->assertCount(0, User::all());
        $this->seed();
        $this->assertNotCount(0, User::all());


        $this->assertCount(0, Employee::all());

        $response = $this->post('kanban/create-kanban-employees', [
            'selectedUsers' =>
                [
                    ['id' => 1,],
                    ['id' => 2,],
                    ['id' => 3,],
                ],
            'role' => "employee"
        ]);

        $this->assertCount(3, Employee::all());
    }

    public function testAUserCanEditAnEmployee()
    {
        $this->testAUserCanCreateSeveralEmployees();
        $this->assertDatabaseHas('kanban_employees', ['user_id' => 1, 'role' => 'employee']);


        $response = $this->post('kanban/create-kanban-employees', [
            'selectedUsers' =>
                [
                    ['id' => 1,],
                ],
            'role' => "admin"
        ]);
        $this->assertDatabaseHas('kanban_employees', ['user_id' => 1, 'role' => 'admin']);
        $this->assertDatabaseMissing('kanban_employees', ['user_id' => 1, 'role' => 'employee']);
    }

    public function testAUserCanDeleteAnEmployee()
    {
        $this->testAUserCanCreateSeveralEmployees();
        $this->assertCount(3, Employee::all());
        $response = $this->post('kanban/delete-kanban-employee/1');
        $this->assertCount(2, Employee::all());
    }
}
