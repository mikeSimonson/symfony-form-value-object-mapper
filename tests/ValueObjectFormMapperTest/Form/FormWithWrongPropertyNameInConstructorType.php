<?php

namespace MikeSimonson\ValueObjectFormMapperTest\Form;


use MikeSimonson\ValueObjectFormMapper\FormMapper;
use MikeSimonson\ValueObjectFormMapperTest\Entity\ParentEntityWithConstructorWithoutTypeHint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormWithWrongPropertyNameInConstructorType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('propertyWithWrongName')
            ->add('age')
            ->add('bla')
            ->setDataMapper(new FormMapper())
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
        return 'form_with_wrong_property_name';
    }

}
