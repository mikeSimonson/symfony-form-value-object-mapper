<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Entity;


class EmptyEntityConstructor
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $age;
    
    public function __construct()
    {
    }

    public static function fromArray($array)
    {
        $object = new EmptyEntityConstructor();
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
