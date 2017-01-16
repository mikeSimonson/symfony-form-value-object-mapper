<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Entity;


class ConstructorLessEntity
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var boolean
     */
    private $boolean;
    
    public static function fromArray($array)
    {
        $object = new ConstructorLessEntity();
        foreach ($array as $property => $value){
            if ($property == 'datetime') {
                $value = new \DateTime($value);
            }
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
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    public function isBoolean()
    {
        return $this->boolean;
    }

    public function setBoolean($boolean)
    {
        $this->boolean = $boolean;
    }
}
