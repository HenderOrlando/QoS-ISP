<?php

namespace QoS\MedicionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MedicionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('upload')
            ->add('unidadUpload')
            ->add('download')
            ->add('unidadDownload')
            ->add('promedio')
            ->add('unidadPromedio')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QoS\MedicionesBundle\Entity\Medicion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_medicionesbundle_medicion';
    }
}
