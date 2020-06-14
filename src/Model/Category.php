<?php

namespace Hardkode\Model;

use ORM\Entity;

/**
 * Class Article
 * @package Hardkode\Model
 */
class Category extends Entity
{

    protected static $tableName = 'hk_article';

    protected static $relations = [
        'author'   => [User::class, ['userId' => 'id']],
        'articles' => [Article::class, 'category']
    ];

}