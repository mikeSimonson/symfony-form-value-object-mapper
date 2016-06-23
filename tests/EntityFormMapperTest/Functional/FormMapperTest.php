<?php

namespace MikeSimonson\EntityFormMapperTest\Functional;

use MikeSimonson\EntityFormMapper\Exception\FormMapperException;
use MikeSimonson\EntityFormMapper\Exception\InvalidArgumentFormMapperException;
use MikeSimonson\EntityFormMapperTest\Entity\ChildAndParentEntityWithConstructor;
use MikeSimonson\EntityFormMapperTest\Entity\EntityConstructorWithStrongTypehint;
use MikeSimonson\EntityFormMapperTest\Entity\EntityConstructorWithTypehintAllowingNull;
use MikeSimonson\EntityFormMapperTest\Entity\EntityWithMissingGetter;
use MikeSimonson\EntityFormMapperTest\Entity\EntityWithMissingSetter;
use MikeSimonson\EntityFormMapperTest\Entity\EntityWithSetterWithoutTypehintAllowingNull;
use MikeSimonson\EntityFormMapperTest\Entity\EntityWithSetterWithStrongTypehint;
use MikeSimonson\EntityFormMapperTest\Entity\EntityWithSetterWithTypehint;
use MikeSimonson\EntityFormMapperTest\Entity\EntityWithSetterWithTypehintAllowingNull;
use MikeSimonson\EntityFormMapperTest\Entity\ParentEntityWithConstructorWithoutTypeHint;
use MikeSimonson\EntityFormMapperTest\Entity\ParentEntityWithConstructorWithTypeHint;
use MikeSimonson\EntityFormMapperTest\Entity\ConstructorLessEntity;
use MikeSimonson\EntityFormMapperTest\Entity\ConstructorLessEntityWithConstructorLessParentClass;
use MikeSimonson\EntityFormMapperTest\Entity\EmptyEntityConstructor;
use MikeSimonson\EntityFormMapperTest\Entity\EntityConstructorWithoutTypehint;
use MikeSimonson\EntityFormMapperTest\Entity\EntityConstructorWithTypehint;
use MikeSimonson\EntityFormMapperTest\Form\ChildAndParentWithConstructorType;
use MikeSimonson\EntityFormMapperTest\Form\EntityConstructorWithStrongTypehintType;
use MikeSimonson\EntityFormMapperTest\Form\EntityConstructorWithTypehintAllowingNullType;
use MikeSimonson\EntityFormMapperTest\Form\EntityWithMissingGetterType;
use MikeSimonson\EntityFormMapperTest\Form\EntityWithMissingSetterType;
use MikeSimonson\EntityFormMapperTest\Form\EntityWithSetterWithoutTypehintAllowingNullType;
use MikeSimonson\EntityFormMapperTest\Form\EntityWithSetterWithStrongTypehintType;
use MikeSimonson\EntityFormMapperTest\Form\EntityWithSetterWithTypehintAllowingNullType;
use MikeSimonson\EntityFormMapperTest\Form\EntityWithSetterWithTypehintNotRequiredType;
use MikeSimonson\EntityFormMapperTest\Form\EntityWithSetterWithTypehintType;
use MikeSimonson\EntityFormMapperTest\Form\FormWithUnmappedFieldType;
use MikeSimonson\EntityFormMapperTest\Form\ParentEntityWithConstructorWithoutTypeHintType;
use MikeSimonson\EntityFormMapperTest\Form\ParentEntityWithConstructorWithTypeHintType;
use MikeSimonson\EntityFormMapperTest\Form\ConstructorLessEntityType;
use MikeSimonson\EntityFormMapperTest\Form\ConstructorLessEntityWithConstructorLessParentClassType;
use MikeSimonson\EntityFormMapperTest\Form\EntityConstructorWithoutTypehintType;
use MikeSimonson\EntityFormMapperTest\Form\EntityConstructorWithTypehintType;
use MikeSimonson\EntityFormMapperTest\Form\EmptyEntityConstructorType;
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

        $this->assertEmpty($form->getErrors());
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
        $this->setExpectedException(FormMapperFormMapperException::class,
            'Unable to find a getter for the property name on the form entity_with_missing_getter.');
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

    public function testShouldThrowAnExceptionIfFieldNotMappedByEntity()
    {
        $formData = [
            'someMissingEntityProperty' => 'boom',
        ];

        $type = new FormWithUnmappedFieldType();
        $form = $this->factory->create($type);

        // submit the data to the form directly
        $this->setExpectedException(FormMapperFormMapperException::class,
            'Unable to find the method setSomeMissingEntityProperty');
        $form->submit($formData);
    }

    public function testShouldThrowAnExceptionIfSetterIsMissing()
    {
        $formData = [
            'name' => 'test2',
            'age' => '41',
        ];

        $type = new EntityWithMissingSetterType();
        $object = EntityWithMissingSetter::fromArray($formData);
        $this->setExpectedException(FormMapperFormMapperException::class, 'Unable to find the method setName');
        $this->runFormTestCreateAndUpdate($object, $formData, $type);
    }

    public function testShouldNotSetAPropertyIfItsTypeHintedAndNotRequiredByTheForm()
    {
        $formData = [
            'name' => 'test2',
            'age' => '41',
            'datetime' => null,
        ];

        $objectData = [
            'name' => 'test1',
            'age' => '42',
            'datetime' => '2015-02-03',
        ];

        $formType = new EntityWithSetterWithTypehintNotRequiredType();
        $expected = EntityWithSetterWithStrongTypehint::fromArray($objectData);

        $form = $this->getUpdateEntityForm($expected, $formType);
        $this->runFormTest($expected, $formData, $form);
    }

    public function testShouldTriggerAnInvalidArgumentExceptionIfSetterTypehintedAndNotAllowingNull()
    {
        $formData = [
            'name' => 'test2',
            'age' => '41',
            'datetime' => null,
        ];

        $objectData = [
            'name' => 'test1',
            'age' => '42',
            'datetime' => '2015-02-03',
        ];

        $formType = new EntityWithSetterWithStrongTypehintType();
        $expected = EntityWithSetterWithStrongTypehint::fromArray($objectData);

        $form = $this->getUpdateEntityForm($expected, $formType);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

        $this->assertContains('datetime is required', $form->getErrors()[0]->getMessage());
    }
}
