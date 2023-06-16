<?php

namespace App\Enums;

enum GroupUserType: string
{
    case TYPE_OWNER = 'owner';
    case TYPE_MEMBER = 'member';

    public function notUsableToJoinGroup(): bool
    {
        return in_array($this, [
            self::TYPE_OWNER,
        ]);
    }
}
