<?php

namespace EntityFormMapperTest\Functional;

use EntityFormMapperTest\Entity\ChildAndParentEntityWithConstructor;
use EntityFormMapperTest\Entity\EntityConstructorWithTypehintAllowingNull;
use EntityFormMapperTest\Entity\EntityWithSetterWithoutTypehintAllowingNull;
use EntityFormMapperTest\Entity\EntityWithSetterWithTypehint;
use EntityFormMapperTest\Entity\EntityWithSetterWithTypehintAllowingNull;
use EntityFormMapperTest\Entity\ParentEntityWithConstructorWithoutTypeHint;
use EntityFormMapperTest\Entity\ParentEntityWithConstructorWithTypeHint;
use EntityFormMapperTest\Entity\ConstructorLessEntity;
use EntityFormMapperTest\Entity\ConstructorLessEntityWithConstructorLessParentClass;
use EntityFormMapperTest\Entity\EmptyEntityConstructor;
use EntityFormMapperTest\Entity\EntityConstructorWithoutTypehint;
use EntityFormMapperTest\Entity\EntityConstructorWithTypehint;
use EntityFormMapperTest\Form\ChildAndParentWithConstructorType;
use EntityFormMapperTest\Form\EntityConstructorWithTypehintAllowingNullType;
use EntityFormMapperTest\Form\EntityWithSetterWithoutTypehintAllowingNullType;
use EntityFormMapperTest\Form\EntityWithSetterWithTypehintAllowingNullType;
use EntityFormMapperTest\Form\EntityWithSetterWithTypehintType;
use EntityFormMapperTest\Form\ParentEntityWithConstructorWithoutTypeHintType;
use EntityFormMapperTest\Form\ParentEntityWithConstructorWithTypeHintType;
use EntityFormMapperTest\Form\ConstructorLessEntityType;
use EntityFormMapperTest\Form\ConstructorLessEntityWithConstructorLessParentClassType;
use EntityFormMapperTest\Form\EntityConstructorWithoutTypehintType;
use EntityFormMapperTest\Form\EntityConstructorWithTypehintType;
use EntityFormMapperTest\Form\EmptyEntityConstructorType;
use Symfony\Component\Form\Test\TypeTestCase;

class FormMapperTest extends TypeTestCase
{
    
    private function runFormTest($expected, $formData, $formType)
    {
        $form = $this->factory->create($formType);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testMapEntityWithoutConstructor()
    {
        $formData = [
            'name' => 'test',
            'datetime' => '2016-05-02',
        ];
        $type = new ConstructorLessEntityType();
        $object = ConstructorLessEntity::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithEmptyConstructor()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
        ];
        $type = new EmptyEntityConstructorType();
        $object = EmptyEntityConstructor::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithConstructorWithoutTypeHint()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
        ];

        $type = new EntityConstructorWithoutTypehintType();
        $object = EntityConstructorWithoutTypehint::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithConstructorWithTypeHint()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
            'thing' => [
                'name' => 'subtest',
                'age' => 43,
            ],
        ];

        $type = new EntityConstructorWithTypehintType();
        $object = EntityConstructorWithTypehint::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithoutConstructorAndWithParentClass()
    {
        $formData = [
            'name' => 'test',
            'datetime' => '2016-05-02',
            'subname' => 'subtest',
        ];

        $type = new ConstructorLessEntityWithConstructorLessParentClassType();
        $object = ConstructorLessEntityWithConstructorLessParentClass::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithConstructorWithoutTypehintInParentClassAndWithoutInChildClass()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
            'bla' => 'bla',
        ];

        $type = new ParentEntityWithConstructorWithoutTypeHintType();
        $object = ParentEntityWithConstructorWithoutTypeHint::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithConstructorInParentClassAndInChildClass()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
            'thing' => [
                'name' => 'subtest',
                'age' => '43',
            ],
            'bla' => 'bla',
        ];

        $type = new ChildAndParentWithConstructorType();
        $object = ChildAndParentEntityWithConstructor::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithConstructorInParentClassWithTypeHint()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
            'thing' => [
                'name' => 'subtest',
                'age' => 43,
            ],
            'bla' => 'bla',
        ];

        $type = new ParentEntityWithConstructorWithTypeHintType();
        $object = ParentEntityWithConstructorWithTypeHint::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithConstructorInParentClassWithTypeHintAllowingNull()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
            'thing' => null,
        ];
        
        $type = new EntityConstructorWithTypehintAllowingNullType();
        $object = EntityConstructorWithTypehintAllowingNull::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }

    public function testMapEntityWithSetterWithTypeHint()
    {
        $formData = [
            'name' => 'test2',
            'age' => '41',
            'thing' => [
                'name' => 'test',
                'age' => '42',
                'thing' => [
                    'name' => 'subtest',
                    'age' => 43,
                ],
            ],
        ];

        $type = new EntityWithSetterWithTypehintType();
        $object = EntityWithSetterWithTypehint::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }
    
    public function testMapEntityWithSetterAllowingNullWithoutTypeHint()
    {
        $formData = [
            'name' => 'test2',
            'age' => null,
        ];

        $type = new EntityWithSetterWithoutTypehintAllowingNullType();
        $object = EntityWithSetterWithoutTypehintAllowingNull::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }
    
    public function testMapEntityWithSetterAllowingNullWithTypeHint()
    {
        $formData = [
            'name' => 'test2',
            'age' => '41',
            'datetime' => null,
        ];

        $type = new EntityWithSetterWithTypehintAllowingNullType();
        $object = EntityWithSetterWithTypehintAllowingNull::fromArray($formData);
        $this->runFormTest($object, $formData, $type);
    }
}
