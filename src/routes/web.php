<?php

use MohamadZatar\Nafath\Http\Controllers\NafathController;

Route::post('nafath/login', [NafathController::class, 'sendLoginRequest']);
