<?php

use App\Enums\GroupUserType;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

it('can be created using it\'s factory', function () {
    $group = Group::factory()
        ->create();

    $this->assertDatabaseHas('groups', [
        'id' => $group->id,
        'name' => $group->name,
        'description' => $group->description,
        'is_public' => $group->is_public,
    ]);
});

it('has a belongsToMany relation with users', function () {
    $group = Group::factory()
        ->hasAttached(
            User::factory(),
            ['type' => GroupUserType::TYPE_OWNER],
        )
        ->create();

    $this->assertInstanceOf(BelongsToMany::class, $group->users());
});

it('has an accessor "is_private" that returns whether the Group is publicly visible or not', function () {
    $publicGroup = Group::factory()
        ->public()
        ->create();

    $privateGroup = Group::factory()
        ->private()
        ->create();

    $this->assertFalse($publicGroup->is_private);
    $this->assertTrue($privateGroup->is_private);
});
