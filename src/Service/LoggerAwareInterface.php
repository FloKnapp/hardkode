<?php

namespace Hardkode\Service;

use Psr\Log\LoggerInterface;

/**
 * Interface LoggerAwareInterface
 * @package Hardkode\Service
 */
interface LoggerAwareInterface extends \Psr\Log\LoggerAwareInterface
{

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface;

}