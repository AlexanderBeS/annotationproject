<?php


namespace Budget\Service;


use Budget\Annotation\Validator\EmptyValue;
use Budget\Annotation\Validator\Length;

class ValidatorService
{
    use ContainerTrait;

    /** @var array */
    private $annotations;

    /**
     * @param string $field
     * @param $value
     * @return bool
     */
    public function validate(string $field, $value)
    {
        $this->annotations = $this->getContainer()['annotations'];

        $fieldAnnotation = $this->annotations[$field];

        //var_dump($fieldAnnotation);

        if ($fieldAnnotation instanceof Length) {
            $value = mb_strlen($value);
            if ($value > $fieldAnnotation->max) {
                throw new \InvalidArgumentException($fieldAnnotation->maxMessage);
            }
            if ($value < $fieldAnnotation->min) {
                throw new \InvalidArgumentException($fieldAnnotation->minMessage);
            }
        }

        if ($fieldAnnotation instanceof EmptyValue) {
            $value = empty($value);
            if ($value) {
                throw new \InvalidArgumentException($fieldAnnotation->message);
            }
        }

        return true;
    }
}