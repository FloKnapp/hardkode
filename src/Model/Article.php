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
        'comments' => [Comment::class, 'article']
    ];

    private $author;

    private $title;

    private $teaser;

    private $content;

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->getRelated('author');
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

}