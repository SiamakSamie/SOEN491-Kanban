<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use SiamakSamie\Kanban\Models\Employee;

class EmployeeController extends Controller
{
    public function getEmployees()
    {
        return Employee::with('user')->get();
    }

    public function createEmployees(Request $request)
    {
        $employeeData = $request->all();

        try {

            foreach ($employeeData['selectedUsers'] as $user) {

                $employee = Employee::updateOrCreate(
                    ['user_id' => $user['id']],
                    ['role' => $employeeData['role'],]
                );
            }

        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response(['success' => 'true'], 200);
    }

    public function deleteEmployee($id)
    {
        try {
            $employee = Employee::find($id);
            $employee->delete();

        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response(['success' => 'true'], 200);
    }

    //PARENT USERS

    public function getAllUsers()
    {
        return User::orderBy('name')->take(10)->get();
    }

    public function getSomeUsers($search)
    {
        return User::where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })->orderBy('name')->take(10)->get();
    }
}
