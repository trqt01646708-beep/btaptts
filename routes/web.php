<?php

use Illuminate\Support\Facades\Route;

Route::get('/user/{id}', function ($id) {
    $users = [
		[
			'id' => 1,
			'name' => 'Trần Văn A',
			'gender' => 'Nam'
		],
		[
			'id' => 2,
			'name' => 'Trần Văn B', 
			'gender' => 'Nữ'
		],
        [
            'id' => 3,
            'name' => 'Trần Văn C', 
            'gender' => 'Nam'
        ]
    ];

    // Tìm user theo id
    $user = null;
    foreach ($users as $u) {
        if ($u['id'] == $id) {
            $user = $u;
            break;
        }
    }

    return view('user', compact('user'));
});
