<?php

namespace Hardkode\Service;

/**
 * Trait EntityManagerAwareTrait
 * @package Hardkode\Service
 */
trait EntityManagerAwareTrait
{

    /** @var EntityManager */
    private $entityManager;

    /**
     * @return mixed
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager): void
    {
        $this->entityManager = $entityManager;
    }

}