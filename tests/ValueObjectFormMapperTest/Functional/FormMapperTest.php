<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Functional;

use MikeSimonson\ValueObjectFormMapper\Exception\FormMapperException;
use MikeSimonson\ValueObjectFormMapper\Exception\InvalidArgumentFormMapperException;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ChildAndParentEntityWithConstructor;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityConstructorWithStrongTypehint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityConstructorWithTypehintAllowingNull;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityWithMissingGetter;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityWithMissingSetter;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityWithSetterWithoutTypehintAllowingNull;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityWithSetterWithStrongTypehint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityWithSetterWithTypehint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityWithSetterWithTypehintAllowingNull;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ParentEntityWithConstructorWithoutTypeHint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ParentEntityWithConstructorWithTypeHint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ConstructorLessEntity;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ConstructorLessEntityWithConstructorLessParentClass;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EmptyEntityConstructor;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityConstructorWithoutTypehint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityConstructorWithTypehint;
use MikeSimonson\ValueObjectFormMapperTest\Form\ChildAndParentWithConstructorType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityConstructorWithStrongTypehintType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityConstructorWithTypehintAllowingNullType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityWithMissingGetterType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityWithMissingSetterType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityWithSetterWithoutTypehintAllowingNullType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityWithSetterWithStrongTypehintType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityWithSetterWithTypehintAllowingNullType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityWithSetterWithTypehintNotRequiredType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityWithSetterWithTypehintType;
use MikeSimonson\ValueObjectFormMapperTest\Form\FormWithUnmappedFieldType;
use MikeSimonson\ValueObjectFormMapperTest\Form\FormWithWrongPropertyNameInConstructorType;
use MikeSimonson\ValueObjectFormMapperTest\Form\ParentEntityWithConstructorWithoutTypeHintType;
use MikeSimonson\ValueObjectFormMapperTest\Form\ParentEntityWithConstructorWithTypeHintType;
use MikeSimonson\ValueObjectFormMapperTest\Form\ConstructorLessEntityType;
use MikeSimonson\ValueObjectFormMapperTest\Form\ConstructorLessEntityWithConstructorLessParentClassType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityConstructorWithoutTypehintType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EntityConstructorWithTypehintType;
use MikeSimonson\ValueObjectFormMapperTest\Form\EmptyEntityConstructorType;
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
        $this->setExpectedException(FormMapperException::class,
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
        $this->setExpectedException(FormMapperException::class,
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
        $this->setExpectedException(FormMapperException::class, 'Unable to find the method setName');
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
    
    public function testShouldThrowAFormMapperExceptionIfRequiredParameterOfConstructorIsMissingFromTheForm()
    {
        $formData = [
            'name' => 'test',
            'age' => '42',
            'thing' => [
                'wrongPropertyName' => 'subtest',
                'age' => 43,
            ],
        ];

        $type = new FormWithWrongPropertyNameInConstructorType();
        $this->setExpectedException(FormMapperException::class, 'The constructor required parameter "name" is not in the form.');
        $this->runFormTestCreateAndUpdate(null, $formData, $type);
    }
}
