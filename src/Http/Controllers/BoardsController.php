<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SiamakSamie\Kanban\Models\Board;


class BoardsController extends Controller
{
    public function getBoards()
    {
        return Board::orderBy('name')->with('members')->get();
    }

    public function createBoard(Request $request)
    {
        $rules = [
            'name' => 'required|unique:kanban_boards,name,'.$request->input('id').',id',
        ];

        $customMessages = [
            'name.required' => 'Kanban board name is required.',
            'name.unique' => 'Kanban board name must be unique.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response([
                'success' => 'false',
                'message' => implode(' ', $validator->messages()->all()),
            ], 400);
        }

        try {
            if ($request->filled('id')) {
                Board::where('id', $request->input('id'))->update([
                    'name' => $request->input('name'),
                ]);
            } else {
                $board = Board::create([
                    'name' => $request->input('name'),
                ]);
            }

        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }

        return response(['success' => 'true'], 200);
    }

    public function deleteBoard($id)
    {
        try {
            $board = Board::find($id);
            $board->delete();

        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response(['success' => 'true'], 200);
    }
}
