<?php

namespace Hardkode\Model;

use ORM\Entity;

/**
 * Class Comment
 * @package Hardkode\Model
 */
class Comment extends Entity
{

    protected static $tableName = 'hk_comment';

    protected static $relations = [
        'author'  => [User::class, ['userId' => 'id']],
        'article' => [Article::class, ['articleId' => 'id']]
    ];

}