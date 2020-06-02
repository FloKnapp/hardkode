<?php


namespace Hardkode\Service;


interface EntityManagerAwareInterface
{

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager): void;

}