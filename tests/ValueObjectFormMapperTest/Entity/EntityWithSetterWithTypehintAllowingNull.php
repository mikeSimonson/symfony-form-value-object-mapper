<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Entity;


class EntityWithSetterWithTypehintAllowingNull
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
     * @var \DateTime
     */
    private $datetime;
    
    public static function fromArray($array)
    {
        $object = new self();
        foreach ($array as $property => $value){
            if ($property == 'datetime' && $value !== null) {
                $value = new \DateTime($value);
            }
            $object->{'set' . ucfirst($property)}($value);
        }
        
        return $object;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime(\DateTime $datetime = null)
    {
        $this->datetime = $datetime;
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
