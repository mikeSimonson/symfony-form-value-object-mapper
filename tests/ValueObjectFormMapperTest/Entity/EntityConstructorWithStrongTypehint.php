<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Entity;

use DateTime;


class EntityConstructorWithStrongTypehint
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
     * @var DateTime
     */
    private $thing;


    public function __construct(DateTime $thing)
    {
        $this->thing = $thing;
    }

    public static function fromArray($array)
    {
        $object = new self($array['thing']);
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
     * @return DateTime
     */
    public function getThing()
    {
        return $this->thing;
    }

    /**
     * @param DateTime $thing
     */
    public function setThing($thing)
    {
        $this->thing = $thing;
    }
    
}
