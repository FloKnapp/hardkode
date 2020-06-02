<?php

namespace Hardkode\Model;

use ORM\Entity;

/**
 * Class Comment
 * @package Hardkode\Model
 *
 * @property int    $id
 * @property int    $articleId
 * @property int    $userId
 * @property string $content
 */
class Comment extends Entity
{

    protected static $tableName = 'hk_comment';

    protected static $relations = [
        'author'  => [User::class, ['userId' => 'id']],
        'article' => [Article::class, ['articleId' => 'id']]
    ];

}