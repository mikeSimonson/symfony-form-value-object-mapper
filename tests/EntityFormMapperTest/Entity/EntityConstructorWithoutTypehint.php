<?php

namespace MikeSimonson\EntityFormMapperTest\Entity;


class EntityConstructorWithoutTypehint
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $age;


    public function __construct($name)
    {
        $this->name = $name;
    }

    public static function fromArray($array)
    {
        $object = new self($array['name']);
        foreach ($array as $property => $value){
            $object->{'set' . ucfirst($property)}($value);
        }
        
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

}
