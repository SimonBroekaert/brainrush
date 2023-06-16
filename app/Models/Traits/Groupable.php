<?php

namespace App\Models\Traits;

use App\Enums\GroupUserType;
use App\Models\Group;

trait Groupable
{
    public function createGroup(array $attributes): Group
    {
        $group = Group::create($attributes);

        $this->groups()
            ->attach($group, [
                'type' => GroupUserType::TYPE_OWNER,
            ]);

        return $group;
    }

    public function joinGroup(Group $group, ?GroupUserType $userType = GroupUserType::TYPE_MEMBER): void
    {
        if ($userType->notUsableToJoinGroup()) {
            throw new \InvalidArgumentException("{$userType->value} is not a valid user type to join a group");
        }

        $this->groups()
            ->attach($group, [
                'type' => $userType,
            ]);
    }

    public function leaveGroup(Group $group): void
    {
        // User can't leave a group if he is the owner
        if ($this->isOwnerOfGroup($group)) {
            throw new \InvalidArgumentException('User is the owner of the group');
        }

        $this->groups()
            ->detach($group);
    }

    public function updateGroupUserType(Group $group, GroupUserType $userType): void
    {
        $this->groups()
            ->updateExistingPivot($group, [
                'type' => $userType,
            ]);
    }

    public function isOwnerOfGroup(Group $group): bool
    {
        return $this->groups()
            ->wherePivot('type', GroupUserType::TYPE_OWNER)
            ->wherePivot('group_id', $group->id)
            ->exists();
    }
}
