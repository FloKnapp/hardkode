<?php

namespace Hardkode\Model;

use ORM\Entity;

/**
 * Class Article
 * @package Hardkode\Model
 */
class Article extends Entity
{

    protected static $tableName = 'hk_article';

    protected static $relations = [
        'author'   => [User::class, ['userId' => 'id']],
        'comments' => [Comment::class, 'article'],
        'category' => [Category::class, ['categoryId' => 'id']]
    ];

}