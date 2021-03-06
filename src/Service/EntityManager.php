<?php

namespace Hardkode\Service;

use ORM\EntityManager as ORMEntityManager;

/**
 * Class Database
 * @package Hardkode\Service
 */
class EntityManager extends ORMEntityManager
{

    /**
     * Database constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (empty($options)) {

            $options = [
                EntityManager::OPT_CONNECTION => [
                    'sqlite',
                    __DIR__ . '/../../database/db.sqlite'
                ],
                \PDO::SQLITE_ATTR_OPEN_FLAGS => "COLLATION NOCASE"
            ];

        }

        parent::__construct($options);
    }

}