<?php

namespace EntityFormMapperTest\Entity;


class EntityWithMissingGetter
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
            $object->{'set' . ucfirst($property)}($value);
        }
        
        return $object;
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
