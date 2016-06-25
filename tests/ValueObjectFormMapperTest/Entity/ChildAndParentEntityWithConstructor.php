<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Entity;


class ChildAndParentEntityWithConstructor extends EntityConstructorWithTypehint
{

    /**
     * @var string
     */
    private $bla;
    
    public function __construct(EntityConstructorWithoutTypehint $thing, $bla)
    {
        parent::__construct($thing);
        $this->bla = $bla;
    }

    public static function fromArray($array)
    {
        $object = new EntityConstructorWithoutTypehint($array['thing']['name']);
        $object->setAge($array['thing']['age']);
        $object = new self($object, $array['bla']);
        $object->setAge($array['age']);
        $object->setName($array['name']);

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
