<?php

namespace Hardkode\Service;

/**
 * Class Translate
 * @package Hardkode\Service
 */
class Translator
{

    /** @var string */
    private $country;

    /** @var array */
    private $translationData;

    /**
     * Translator constructor.
     * @param string $country
     */
    public function __construct(string $country = 'de')
    {
        $this->country = $country;

        $this->translationData = json_decode(
            file_get_contents(
                __DIR__ . '/../../config/translations/' . $country . '.json'
            ), true)
        ;
    }

    /**
     * @param string $translationKey
     *
     * @return string
     */
    public function translate(string $translationKey = 'none')
    {
        return $this->translationData[$translationKey] ?? $translationKey;
    }

}