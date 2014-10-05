<?php

namespace QoS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProveedorType extends AbstractType
{
    
    public function __construct() {
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('holgura', null, array(
                'label'     =>  'Holgura de las mediciones para el proveedor',
                'label_attr'=>  array(
                    'class'         =>  'sr-only',
                    'placeholder'   =>  'Holgura de las mediciones para el proveedor',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Holgura de las mediciones para el proveedor',
                ),
            ))
            ->add('unidadHolgura', null, array(
                'label'     =>  'Unidad de la Holgura',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Unidad de la Holgura',
                ),
            ))
            ->add('abreviacion', null, array(
                'label'     =>  'Abreviación del nombre del Proveedor',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Abreviación del nombre del Proveedor',
                ),
            ))
            ->add('nombre', null, array(
                'label'     =>  'Nombre del Proveedor',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Nombre del Proveedor',
                ),
            ))
            ->add('telefono', null, array(
                'label' => 'Teléfono del Proveedor',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Teléfono del Proveedor',
                ),
            ))
            ->add('direccion', null, array(
                'label' => 'Direccion de la Institucion',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Dirección del Proveedor',
                ),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QoS\AdminBundle\Entity\Proveedor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_adminbundle_proveedor';
    }
}
