<?php

namespace EntityFormMapper;


use EntityFormMapper\Exception\FormMapperException;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\FormError;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Reflection\ClassReflection;
use EntityFormMapper\Exception\InvalidArgumentException;

class FormMapper
{

    public function mapDataToForms($data, $form)
    {
        $form = iterator_to_array($form);
        foreach($form as $key => $formElement) {
            if ($formElement instanceof Button) {
                continue;
            }
            if ($data) {
                $form[$key]->setData($this->getData($data, $key, $form[$key]->getParent()->getConfig()->getName()));
            }
        }

        return $form;
    }

    public function mapFormsToData($form, &$data, $class = null)
    {
        $form = iterator_to_array($form);
        $formElements = array_keys($form);

        if ($class === null ) {
            //Get The class that is mapped to this form
            $class = $form[$formElements[0]]->getParent()->getConfig()->getDataClass();
        }

        try {
            if (!$data instanceof $class) { //trying to create a new object
                $data = $this->instantiateObject($class, $form);
            }
            $this->setAllThePropertiesOnTheObject($data, $form);
        } catch (InvalidArgumentException $e) {
            $formElement = reset($form);
            $formElement->getParent()->addError(new FormError($e->getMessage()));
            if (is_callable([$data, 'getId']) && $data->getId() == null) { //Trying to create a new object
                $data = null;
            }
        }
    }

    private function setAllThePropertiesOnTheObject($obj, $form)
    {
        foreach($form as $propertyName => $formElement) {
            if ($formElement instanceof Button) {
                continue;
            }

            $setterName = 'set' . ucfirst($propertyName);
            $class = get_class($obj);
            $class = $this->getClassImplementingMethod($class, $setterName);
            if ($class === false) {
                throw new FormMapperException('Unable to find the method ' . $setterName . ' in object of class .' .
                $class);
            }

            $isParamTypeHint = $this->getTypeHintFromMethodParam($class, $setterName);

            /**
             * If form value == null
             * and value not required
             * and parameter typehinted
             * we skip the set.
             */
            $data = $formElement->getData();
            $required = $formElement->getConfig()->getRequired();
            if ($required === false && $data === null && $isParamTypeHint !== null) {
                continue;
            }

            if ($required === true && $data === null && $isParamTypeHint !== null) {
                throw new InvalidArgumentException($propertyName . ' is required.');
            }

            $obj->{$setterName}($data);
        }
    }

    private function findPropertySetterName($property, $object)
    {
        $accessors = ['get', 'add'];
        $class = get_class($object);

        foreach ($accessors as $accessor) {
            $methodName = $accessor . ucfirst($property);
            $class = $this->getClassImplementingMethod($class, $methodName);

            if ($class !== false) {
                return $methodName;
            }
        }

        throw new FormMapperException('Unable to find a method to set or add ' . $property. ' in object of class .' .
                $class);
    }

    private function getClassImplementingMethod($class, $method)
    {
        $reflectionClass = new ClassReflection($class);
        $reflectionClassGenerator = ClassGenerator::fromReflection($reflectionClass);
        if ($reflectionClassGenerator->getMethod($method) === false) {
            if ($reflectionClass->getParentClass() === false) {
                throw new FormMapperException('Unable to find the method ' . $method);
            }

            return $this->getClassImplementingMethod($reflectionClass->getParentClass()->getName(), $method);
        }

        return $class;
    }

    private function instantiateObject($class, $form)
    {
        $reflectionClass = new \ReflectionClass($class);
        $reflectionConstructor = $reflectionClass->getConstructor();
        if ($reflectionConstructor === null) {
            return $reflectionClass->newInstanceWithoutConstructor();
        }
        $reflectionParameters = $reflectionConstructor->getParameters();
        $nbOfRequiredParameters = $reflectionConstructor->getNumberOfRequiredParameters();
        $params = [];
        $i=0;
        foreach($reflectionParameters as $name => $param) {
            if ($i === $nbOfRequiredParameters) {
                break;
            }
            $i++;
            
            $classWithConstructor = $this->getClassImplementingMethod($class, '__construct');

            $typeHint = $this->getTypeHintFromMethodParam($classWithConstructor, '__construct', $param);
            if ($typeHint !== null && $form[$param->getName()]->getData() === null) {
                throw new InvalidArgumentException('The parameter ' . $param->getName() . ' from the constructor of the  class ' .
                $class . ' cannot be null');
            }
            $params[] = $form[$param->getName()]->getData();
        }

        return $reflectionClass->newInstanceArgs($params);
    }

    private function getData($data, $propertyName, $formName) {
        $getterName = 'get' . ucfirst($propertyName);
        if (is_callable([$data, $getterName])) {
            return $data->{$getterName}();
        }

        throw new FormMapperException('Unable to find a getter for the property ' . $propertyName . ' on the form ' . $formName . '.');
    }

    private function getTypeHintFromMethodParam($class, $methodName, $param = null)
    {
        $reflectionClass = new ClassReflection($class);
        $reflectionClass = ClassGenerator::fromReflection($reflectionClass);
        $method = $reflectionClass->getMethod($methodName);
        $parameters = $method->getParameters();

        if ($param === null) {
            return array_values($parameters)[0]->getType();
        }

        return  $parameters[$param->getName()]->getType();
    }
}
