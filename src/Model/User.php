<?php

namespace Hardkode\Model;

use ORM\Entity;

/**
 * Class User
 * @package Hardkode\Model
 */
class User extends Entity
{

    protected static $tableName = 'hk_user';

    protected static $relations = [
        'articles' => [Comment::class, 'article'],
        'roles' => [Role::class, ['id' => 'user_id'], 'users', 'hk_user_role']
    ];

}