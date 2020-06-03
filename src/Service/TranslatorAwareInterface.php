<?php

namespace Hardkode\Service;

/**
 * Interface TranslatorAwareInterface
 * @package Hardkode\Service
 */
interface TranslatorAwareInterface
{

    /**
     * @return Translator
     */
    public function getTranslator(): Translator;

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator): void;

}