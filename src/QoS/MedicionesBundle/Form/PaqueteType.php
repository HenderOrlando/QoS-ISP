<?php

namespace QoS\MedicionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaqueteType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array(
                'label'     =>  'Nombre del Archivo',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Nombre del Archivo',
                ),
            ))
            ->add('file', null, array(
                'label'     =>  'Archivo a cargar',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Archivo a cargar',
                ),
            ))
            ->add('path', null, array(
                'label'     =>  'Url del Archivo',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Url del Archivo',
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
            'data_class' => 'QoS\MedicionesBundle\Entity\Paquete'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_medicionesbundle_paquete';
    }
}
