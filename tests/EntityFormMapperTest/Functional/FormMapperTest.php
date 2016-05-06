<?php

namespace EntityFormMapperTest\Functional;

use EntityFormMapper\Exception\FormMapperException;
use EntityFormMapper\Exception\InvalidArgumentException;
use EntityFormMapperTest\Entity\ChildAndParentEntityWithConstructor;
use EntityFormMapperTest\Entity\EntityConstructorWithStrongTypehint;
use EntityFormMapperTest\Entity\EntityConstructorWithTypehintAllowingNull;
use EntityFormMapperTest\Entity\EntityWithMissingGetter;
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
use EntityFormMapperTest\Form\EntityConstructorWithStrongTypehintType;
use EntityFormMapperTest\Form\EntityConstructorWithTypehintAllowingNullType;
use EntityFormMapperTest\Form\EntityWithMissingGetterType;
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
    
    private function runFormTestCreateAndUpdate($expected, $formData, $formType)
    {
        $form = $this->getCreateEntityForm($formType);
        $this->runFormTest($expected, $formData, $form);

        $form = $this->getUpdateEntityForm($expected, $formType);
        $this->runFormTest($expected, $formData, $form);
    }

    private function runFormTest($expected, $formData, $form)
    {
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

    private function getCreateEntityForm($formType)
    {
        return $this->factory->create($formType);
    }

    private function getUpdateEntityForm($expected, $formType)
    {
        return $this->factory->create($formType, $expected);
    }

    public function testMapEntityWithoutConstructor()
    {
        $formData = [
            'name' => 'test',
            'datetime' => '2016-05-02',
        ];
        $type = new ConstructorLessEntityType();
        $object = ConstructorLessEntity::fromArray($formData);
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
    }

    public function testMapEntityWithEmptyConstructor()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
        ];
        $type = new EmptyEntityConstructorType();
        $object = EmptyEntityConstructor::fromArray($formData);
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
    }

    public function testMapEntityWithConstructorWithoutTypeHint()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
        ];

        $type = new EntityConstructorWithoutTypehintType();
        $object = EntityConstructorWithoutTypehint::fromArray($formData);
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
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
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
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
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
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
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
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
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
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
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
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
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
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
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
    }
    
    public function testMapEntityWithSetterAllowingNullWithoutTypeHint()
    {
        $formData = [
            'name' => 'test2',
            'age' => null,
        ];

        $type = new EntityWithSetterWithoutTypehintAllowingNullType();
        $object = EntityWithSetterWithoutTypehintAllowingNull::fromArray($formData);
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
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
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
    }

    /**
     * @dataProvider provideFormTypeThatShouldBeSkipped
     */
    public function testThatTheMapperSkipFormButtons($formTypeThatShouldBeSkipped)
    {
        $formData = [
            'name' => 'test2',
            'age' => '41',
            'datetime' => '2016-05-05',
        ];

        $formType = new EntityWithSetterWithTypehintAllowingNullType();
        $expected = EntityWithSetterWithTypehintAllowingNull::fromArray($formData);

        $form = $this->factory->create($formType);
        $form->add($formTypeThatShouldBeSkipped, $formTypeThatShouldBeSkipped);

        $this->runFormTest($expected, $formData, $form);
    }
    
    public function provideFormTypeThatShouldBeSkipped()
    {
        return [
            ['button'],
            ['reset'],
            ['submit'],
        ];
    }
    
    public function testShouldThrowAnExceptionInCaseOfMissingGetter()
    {
        $formData = [
            'name' => 'test2',
            'age' => '41',
        ];

        $type = new EntityWithMissingGetterType();
        $object = EntityWithMissingGetter::fromArray($formData);
        $this->setExpectedException(FormMapperException::class, 'Unable to find a getter for the property name on the form entity_with_missing_getter.');
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
    }

    public function testShouldTriggerAnInvalidArgumentExceptionInCaseOfNullValueInConstructor()
    {
        $formData = [
            'name' => 'test2',
            'age' => '41',
            'thing' => null,
        ];

        $type = new EntityConstructorWithStrongTypehintType();
        $form = $this->factory->create($type);
        
        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(null, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
