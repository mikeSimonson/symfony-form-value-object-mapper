<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Entity;


class ParentEntityWithConstructorWithTypeHint extends EntityConstructorWithTypehint
{

    /**
     * @var string
     */
    private $bla;
    
    public static function fromArray($array)
    {
        $object = new EntityConstructorWithoutTypehint($array['thing']['name']);
        $object->setAge($array['thing']['age']);
        $object = new ParentEntityWithConstructorWithTypeHint($object);
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
