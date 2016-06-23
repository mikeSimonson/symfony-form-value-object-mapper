<?php

namespace MikeSimonson\EntityFormMapperTest\Entity;


class ConstructorLessEntityWithConstructorLessParentClass extends ConstructorLessEntity
{

    /**
     * @var string
     */
    private $subname;

    
    public static function fromArray($array)
    {
        $object = new ConstructorLessEntityWithConstructorLessParentClass();
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
    public function getSubname()
    {
        return $this->subname;
    }

    /**
     * @param string $subname
     */
    public function setSubname($subname)
    {
        $this->subname = $subname;
    }
    
}
