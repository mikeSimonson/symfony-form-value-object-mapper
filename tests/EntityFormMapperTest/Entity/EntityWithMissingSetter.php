<?php

namespace EntityFormMapperTest\Entity;


class EntityWithMissingSetter
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $age;
    
    public static function fromArray($array)
    {
        $object = new self();
        foreach ($array as $property => $value){
            $object->{$property} = $value;
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
