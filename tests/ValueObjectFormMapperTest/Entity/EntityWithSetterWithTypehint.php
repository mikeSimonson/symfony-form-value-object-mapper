<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Entity;


class EntityWithSetterWithTypehint
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
     * @var EntityConstructorWithTypehint
     */
    private $thing;
    
    public static function fromArray($array)
    {
        $object = new self();
        foreach ($array as $property => $value){
            if ($property == 'thing') {
                $value = EntityConstructorWithTypehint::fromArray($value);
            }
            $object->{$property} = $value;
        }
        
        return $object;
    }

    /**
     * @return EntityConstructorWithTypehint
     */
    public function getThing()
    {
        return $this->thing;
    }

    /**
     * @param EntityConstructorWithTypehint $thing
     */
    public function setThing(EntityConstructorWithTypehint $thing)
    {
        $this->thing = $thing;
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
