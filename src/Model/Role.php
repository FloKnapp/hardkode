<?php

namespace Hardkode\Model;

use ORM\Entity;

/**
 * Class Role
 * @package Hardkode\Model
 *
 * @property int $id
 * @property string $name
 */
class Role extends Entity
{

    protected static $tableName = 'hk_role';

    protected static $relations = [
        'users' => [User::class, ['id' => 'role_id'], 'roles', 'hk_user_role']
    ];

}