<?php

namespace MikeSimonson\EntityFormMapperTest\Form;


use MikeSimonson\EntityFormMapper\FormMapper;
use MikeSimonson\EntityFormMapperTest\Entity\ChildAndParentEntityWithConstructor;
use MikeSimonson\EntityFormMapperTest\Entity\ParentEntityWithConstructorWithTypeHint;
use MikeSimonson\EntityFormMapperTest\Entity\ConstructorLessEntity;
use MikeSimonson\EntityFormMapperTest\Entity\EmptyEntityConstructor;
use MikeSimonson\EntityFormMapperTest\Entity\EntityConstructorWithoutTypehint;
use MikeSimonson\EntityFormMapperTest\Entity\EntityConstructorWithTypehint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChildAndParentWithConstructorType extends AbstractType implements DataMapperInterface
{

    /**
     * @var FormMapper
     */
    private $dataMapper;
    
    public function __construct()
    {
        $this->dataMapper = new FormMapper();
    }

    public function mapDataToForms($data, $forms)
    {
        $this->dataMapper->mapDataToForms($data, $forms);
    }

    public function mapFormsToData($forms, &$data)
    {
        $this->dataMapper->mapFormsToData($forms, $data);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('age')
            ->add('bla')
            ->add('thing', new EntityConstructorWithoutTypehintType())
            ->setDataMapper($this)
            ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ChildAndParentEntityWithConstructor::class,
            'empty_data' => null,
        ));
    }

    public function getName()
    {
            return 'child_and_parent_entity_with_constructor';
    }

}
