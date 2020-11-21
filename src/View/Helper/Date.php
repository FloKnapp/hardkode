<?php

namespace Hardkode\View\Helper;

/**
 * Class Date
 * @package Hardkode\View\Helper
 */
class Date extends AbstractViewHelper
{

    /** @var \DateTime */
    private $date;

    /**
     * @param string $date
     * @return $this
     */
    public function __invoke(?string $date)
    {
        try {
            $this->date = new \DateTime($date, new \DateTimeZone('UTC'));
            $this->date->setTimezone(new \DateTimeZone('Europe/Berlin'));
        } catch (\Exception $e) {
            $this->getLogger()->notice('Invalid date given: ' . $date, ['exception' => $e]);
            $this->date = '...';
        }
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function modify(string $value)
    {
        if ($this->date instanceof \DateTime) {
            $this->date->modify($value);
        }

        return $this;
    }

    /**
     * @param string $format
     * @return string
     */
    public function format(string $format)
    {
        if ($this->date instanceof \DateTime) {
            return $this->date->format($format);
        }

        return $this->date;
    }

}