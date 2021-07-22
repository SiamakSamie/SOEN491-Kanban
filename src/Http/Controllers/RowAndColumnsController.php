<?php

namespace SiamakSamie\Kanban\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SiamakSamie\Kanban\Models\Column;
use SiamakSamie\Kanban\Models\Row;


class RowAndColumnsController extends Controller
{
    public function createOrUpdateRowAndColumns(Request $request)
    {
        $rowData = $request->all();
        try {
            if ($rowData['rowId'] !== null) {
                $updatedRow = Row::where('id', $rowData['rowId'])
                    ->update(['name' => $rowData['name'], 'index' => $rowData['rowIndex']]);
                $rowId = $rowData['rowId'];
            } else {
                $newRow = Row::create([
                    'board_id' => $rowData['boardId'],
                    'name' => $rowData['name'],
                    'index' => $rowData['rowIndex'],
                ]);
                $rowId = $newRow->id;
            }

            foreach ($rowData['columns'] as $key=>$column) {
                if ($column['id'] !== null) {

                    Column::where('id', $column['id'])
                        ->update(['name' => $column['name'], 'index' => $key]);

                } else {
                    Column::create([
                        'row_id' => $rowId,
                        'name' => $column['name'],
                        'index' => $key,
                    ]);
                }
            }

            return Row::where('id', $rowId)->with('columns.tasks')->get();

        } catch (\Exception $e) {
            return response([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function deleteRow($id)
    {
        $column = Row::find($id);
        $column->delete();
    }
}
