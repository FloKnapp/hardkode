<?php

namespace Hardkode\Form\Builder\Type;

use Hardkode\Form\Builder\AbstractType;

/**
 * Class Textarea
 * @package Hardkode\Form\Builder\Type
 */
class Textarea extends AbstractType
{

    /**
     * @return string
     */
    public function render()
    {
        $textareaValue = null;
        $result        = '<textarea';

        if (!empty($this->getValue())) {
            $this->definition['value'] = $this->getValue();
        }

        foreach ($this->definition as $attribute => $value) {

            if ($attribute === 'value') {
                $textareaValue = $value;
                continue;
            }

            $result .= sprintf(' %s="%s"', $attribute, $this->getTranslator()->translate($value));
        }

        $result .= '>' . $textareaValue . '</textarea>';

        return $result;
    }

}