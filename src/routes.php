<?php

Route::namespace('Melit/Utils')
        ->prefix('utils')
        ->as('utils.')
        ->group(function()
        {
           Route::resource('/setting', 'SettingController');
        });
