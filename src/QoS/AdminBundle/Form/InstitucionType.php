<?php

namespace QoS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitucionType extends AbstractType
{
    private $insert;
    
    public function __construct($insert = false) {
        $this->insert = $insert;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('abreviacion', null, array(
                'label'     =>  'Abreviación del nombre de la Institucion',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class' =>  'form-control input-lg',
                    'placeholder'   =>  'Abreviación del nombre de la Institución',
                ),
            ))
            ->add('nombre', null, array(
                'label'     =>  'Nombre de la Institucion',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class' =>  'form-control input-lg',
                    'placeholder'   =>  'Nombre de la Institución',
                ),
            ))
            ->add('telefono', null, array(
                'label' => 'Teléfono de la Institucón',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class' =>  'form-control input-lg',
                    'placeholder'   =>  'Teléfono de la Institución',
                ),
            ))
            ->add('direccion', null, array(
                'label' => 'Direccion de la Institucion',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class' =>  'form-control input-lg',
                    'placeholder'   =>  'Dirección de la Institución',
                ),
            ))
            ->add('proveedores', null, array(
                'label' => 'Proveedores',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class' =>  'form-control input-lg',
                    'placeholder'   =>  'Proveedores',
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
            'data_class' => 'QoS\AdminBundle\Entity\Institucion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_adminbundle_institucion';
    }
}
