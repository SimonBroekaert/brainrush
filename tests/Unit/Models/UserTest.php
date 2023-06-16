<?php

use App\Enums\GroupUserType;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

it('can be created using it\'s factory', function () {
    $user = User::factory()
        ->create();

    /** @var Tests\TestCase $this */
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'username' => $user->username,
        'email' => $user->email,
        'password' => $user->password,
    ]);
});

it('has a belongsToMany relation with groups', function () {
    $user = User::factory()
        ->hasAttached(
            Group::factory(),
            ['type' => GroupUserType::TYPE_OWNER],
        )
        ->create();

    /** @var Tests\TestCase $this */
    $this->assertInstanceOf(BelongsToMany::class, $user->groups());
});

it('can create a group', function () {
    $user = User::factory()
        ->create();

    $groupData = Group::factory()
        ->make()
        ->toArray();

    $group = $user->createGroup($groupData);

    /** @var Tests\TestCase $this */
    $this->assertDatabaseHas('groups', [
        'id' => $group->id,
        'name' => $group->name,
        'description' => $group->description,
        'is_public' => $group->is_public,
    ]);

    $this->assertDatabaseHas('group_user', [
        'user_id' => $user->id,
        'group_id' => $group->id,
        'type' => GroupUserType::TYPE_OWNER,
    ]);
});

it('can join a group', function () {
    $user = User::factory()
        ->create();

    $group = Group::factory()
        ->create();

    $user->joinGroup($group);

    /** @var Tests\TestCase $this */
    $this->assertDatabaseHas('group_user', [
        'user_id' => $user->id,
        'group_id' => $group->id,
        'type' => GroupUserType::TYPE_MEMBER,
    ]);
});

it('should not be able to join a group as "Owner"', function () {
    $user = User::factory()
        ->create();

    $group = Group::factory()
        ->create();

    /** @var Tests\TestCase $this */
    $this->expectException(InvalidArgumentException::class);

    $user->joinGroup($group, GroupUserType::TYPE_OWNER);

    $this->assertDatabaseMissing('group_user', [
        'user_id' => $user->id,
        'group_id' => $group->id,
        'type' => GroupUserType::TYPE_OWNER,
    ]);
});

it('can leave a group', function () {
    $user = User::factory()
        ->create();

    $group = Group::factory()
        ->create();

    $user->joinGroup($group);

    /** @var Tests\TestCase $this */
    $this->assertDatabaseHas('group_user', [
        'user_id' => $user->id,
        'group_id' => $group->id,
    ]);

    $user->leaveGroup($group);

    $this->assertDatabaseMissing('group_user', [
        'user_id' => $user->id,
        'group_id' => $group->id,
    ]);
});

it('can\'t leave a group if they are the owner', function () {
    $user = User::factory()
        ->create();

    $groupData = Group::factory()
        ->make()
        ->toArray();

    $group = $user->createGroup($groupData);

    /** @var Tests\TestCase $this */
    $this->assertDatabaseHas('group_user', [
        'user_id' => $user->id,
        'group_id' => $group->id,
        'type' => GroupUserType::TYPE_OWNER,
    ]);

    $this->expectException(InvalidArgumentException::class);

    $user->leaveGroup($group);
});

it('can update the user type in a already joined group', function () {
    $user = User::factory()
        ->create();

    $group = Group::factory()
        ->create();

    $user->joinGroup($group);

    /** @var Tests\TestCase $this */
    $this->assertDatabaseHas('group_user', [
        'user_id' => $user->id,
        'group_id' => $group->id,
        'type' => GroupUserType::TYPE_MEMBER,
    ]);

    $user->updateGroupUserType($group, GroupUserType::TYPE_OWNER);

    $this->assertDatabaseHas('group_user', [
        'user_id' => $user->id,
        'group_id' => $group->id,
        'type' => GroupUserType::TYPE_OWNER,
    ]);
});

it('can check if the user is the owner of a group', function () {
    $user = User::factory()
        ->create();

    $groupData = Group::factory()
        ->make()
        ->toArray();

    $group = $user->createGroup($groupData);

    /** @var Tests\TestCase $this */
    $this->assertTrue($user->isOwnerOfGroup($group));

    $otherGroup = Group::factory()
        ->create();

    $this->assertFalse($user->isOwnerOfGroup($otherGroup));

    $user->joinGroup($otherGroup);

    $this->assertFalse($user->isOwnerOfGroup($otherGroup));
});
