<?php

namespace MikeSimonson\EntityFormMapperTest\Entity;


class EntityConstructorWithTypehintAllowingNull
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $age;

    /**
     * @var EntityConstructorWithoutTypehint
     */
    private $thing;


    public function __construct(EntityConstructorWithoutTypehint $thing = null)
    {
        $this->thing = $thing;
    }

    public static function fromArray($array)
    {
        if ($array['thing'] != null) {
            $object = new EntityConstructorWithoutTypehint($array['thing']['name']);
            $object->setAge($array['thing']['age']);
        } else {
            $object = new EntityConstructorWithoutTypehint(null);
        }
        $object = new self($object);
        $object->setAge($array['age']);
        $object->setName($array['name']);

        return $object;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return EntityConstructorWithoutTypehint
     */
    public function getThing()
    {
        return $this->thing;
    }

    /**
     * @param EntityConstructorWithoutTypehint $thing
     */
    public function setThing($thing)
    {
        $this->thing = $thing;
    }
    
    

}
