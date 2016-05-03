<?php

namespace EntityFormMapperTest\Entity;


class ParentEntityWithConstructorWithoutTypeHint extends EntityConstructorWithoutTypehint
{

    /**
     * @var string
     */
    private $bla;
    
    public static function fromArray($array)
    {
        $object = new self($array['name']);
        $object->setAge($array['age']);
        $object->setName($array['name']);
        $object->setBla($array['bla']);

        return $object;
    }

    /**
     * @return string
     */
    public function getBla()
    {
        return $this->bla;
    }

    /**
     * @param string $bla
     */
    public function setBla($bla)
    {
        $this->bla = $bla;
    }
    
}
