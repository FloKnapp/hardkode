<?php

namespace Hardkode\Service;

use ORM\Exception\IncompletePrimaryKey;
use ORM\Exception\NoEntity;

/**
 * Class User
 * @package Hardkode\Service
 */
class User
{

    /** @var EntityManager */
    private $em;

    /** @var Session */
    private $session;

    /**
     * User constructor.
     * @param EntityManager $em
     * @param Session $session;
     */
    public function __construct(EntityManager $em, Session $session)
    {
        $this->em      = $em;
        $this->session = $session;
    }

    /**
     * @return \Hardkode\Model\User|null
     */
    public function getCurrentUser()
    {
        try {
            return $this->em->fetch(\Hardkode\Model\User::class, $this->session->get('userId'));
        } catch (IncompletePrimaryKey | NoEntity $e) {
            return null;
        }
    }

}