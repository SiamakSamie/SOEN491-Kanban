<?php

    Route::group(['namespace' => 'SiamakSamie\Kanban\Http\Controllers',], function () {
        Route::group(['prefix' => 'kanban',], function () {
            Route::get('/', 'KanbanController@getIndex');
        });
    });
