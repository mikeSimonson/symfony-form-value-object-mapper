<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Form;


use MikeSimonson\ValueObjectFormMapper\FormMapper;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ParentEntityWithConstructorWithoutTypeHint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ParentEntityWithConstructorWithTypeHint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ConstructorLessEntity;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EmptyEntityConstructor;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityConstructorWithoutTypehint;
use MikeSimonson\ValueObjectFormMapperTest\Entity\EntityConstructorWithTypehint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParentEntityWithConstructorWithoutTypeHintType extends AbstractType implements DataMapperInterface
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
            ->setDataMapper($this)
            ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ParentEntityWithConstructorWithoutTypeHint::class,
            'empty_data' => null,
        ));
    }

    public function getName()
    {
            return 'parent_entity_with_constructor_without_typehint';
    }

}
