<?php

Route::group(['middleware' => ['web']], function () {


    Route::group(['namespace' => 'SiamakSamie\Kanban\Http\Controllers',], function () {
        Route::group(['prefix' => 'kanban',], function () {

            // We'll let vue router handle 404 (it will redirect to dashboard)
            Route::fallback('KanbanController@getIndex');

            // All view routes are handled by vue router
            Route::get('/', 'KanbanController@getIndex');
            Route::get('/dashboard', 'KanbanController@getIndex');
            Route::get('/board', 'KanbanController@getIndex');


            //ASYNC ROUTES -----------------------------------------------------------

            //Kanban App Data

            Route::get('/get-dashboard-data', 'KanbanController@getDashboardData');
            Route::get('/get-board-data/{id}', 'KanbanController@getkanbanData');

            // Task

            Route::post('/create-task', 'TaskController@createTaskCard');


            // Board

            Route::get('/get-boards', 'BoardsController@getBoards');
            Route::post('/create-board', 'BoardsController@createBoard');
            Route::post('/delete-board/{id}', 'BoardsController@deleteBoard');


            //Employees + Parent Users

            Route::post('/create-kanban-employees', 'EmployeeController@createEmployees');
            Route::get('/get-kanban-employees', 'EmployeeController@getEmployees');
            Route::post('/delete-kanban-employee/{id}', 'EmployeeController@deleteEmployee');


            Route::get('/get-all-users', 'EmployeeController@getAllUsers');
            Route::get('/get-some-users/{searchTerm}', 'EmployeeController@getSomeUsers');

            // Members

            Route::post('/create-members/{id}', 'MemberController@createMembers');
            Route::post('/delete-member/{id}', 'MemberController@deleteMember');
            Route::get('/get-members/{id}', 'MemberController@getMembers');

            // Columns
            Route::post('/save-row-and-columns', 'RowAndColumnsController@createOrUpdateRowAndColumns');
            Route::post('/delete-row/{id}', 'RowAndColumnsController@deleteRow');

            // Badges
            Route::get('/get-all-badges', 'BadgeController@getAllBadges');

            // Kanban Drag Calls

            Route::post('/get-task-cards-by-column/{id}', 'TaskController@getTaskCardsByColumn');
            Route::post('/update-task-cards-indexes', 'TaskController@updateTaskCardsIndexes');
            Route::post('/update-column-indexes', 'RowAndColumnsController@updateColumnIndexes');
            Route::post('/update-row-indexes', 'RowAndColumnsController@updateRowIndexes');
            Route::post('/update-task-card-row-and-column/{columnId}/{rowId}/{taskCardId}', 'TaskController@updateTaskCardRowAndColumnId');


        });
    });
});
