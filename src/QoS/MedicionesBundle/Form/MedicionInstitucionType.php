<?php

namespace QoS\MedicionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use QoS\AdminBundle\Form\InstitucionType;
use QoS\AdminBundle\Form\ProveedorType;

class MedicionInstitucionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paquete', null, array(
                'label' => 'Tamaño del Paquete a Usar',
                'label_attr'=>  array(
//                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Tamaño del Paquete a Usar',
                ),
            ))
            ->add('proveedor', new ProveedorType(true))
            ->add('institucion', new InstitucionType(true))
//            ->add('medicion', new MedicionType(true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QoS\MedicionesBundle\Entity\MedicionInstitucion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_medicionesbundle_medicioninstitucion';
    }
}
