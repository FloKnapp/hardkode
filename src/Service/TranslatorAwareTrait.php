<?php

namespace Hardkode\Service;

/**
 * Trait TranslatorAwareTrait
 * @package Hardkode\Service
 */
trait TranslatorAwareTrait
{

    /** @var Translator */
    private $translator;

    /**
     * @return Translator
     */
    public function getTranslator(): Translator
    {
        return $this->translator;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator): void
    {
        $this->translator = $translator;
    }

}