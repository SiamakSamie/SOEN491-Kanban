<?php

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

            // Board

            Route::get('/get-boards', 'BoardsController@getBoards');
            Route::post('/create-board', 'BoardsController@createBoard');


        });
    });
