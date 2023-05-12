<?php

use App\Models\User;

it('can be created using it\'s factory', function () {
    $user = User::factory()
        ->create();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'username' => $user->username,
        'email' => $user->email,
        'password' => $user->password,
    ]);
});
