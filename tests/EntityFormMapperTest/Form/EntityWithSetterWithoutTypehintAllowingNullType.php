<?php

namespace EntityFormMapperTest\Form;


use EntityFormMapper\FormMapper;
use EntityFormMapperTest\Entity\ConstructorLessEntity;
use EntityFormMapperTest\Entity\EmptyEntityConstructor;
use EntityFormMapperTest\Entity\EntityWithSetterWithoutTypehintAllowingNull;
use EntityFormMapperTest\Entity\EntityWithSetterWithTypehint;
use EntityFormMapperTest\Entity\EntityWithSetterWithTypehintAllowingNull;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityWithSetterWithoutTypehintAllowingNullType extends AbstractType implements DataMapperInterface
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
            ->setDataMapper($this)
            ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EntityWithSetterWithoutTypehintAllowingNull::class,
            'empty_data' => null,
        ));
    }

    public function getName()
    {
            return 'entity_with_setter_without_typehint_allowing_null';
    }

}
