<?php

namespace QoS\MedicionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MedirInstitucionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformerInstitucion = new DataTransformer\InstitucionTransformer($options['em']);
        $transformerProveedor = new DataTransformer\ProveedorTransformer($options['em']);
        $builder
            ->add('paquete', null, array(
                'label' => 'Paquete a Usar',
                'label_attr'=>  array(
//                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Tamaño del Paquete a Usar',
                ),
            ))
            ->add('numPaquetes', null, array(
                'label' => 'Cantidad de Paquetes',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'title'         =>  'Si deja en blanco podrá detenerlo en cualquier momento',
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Cantidad de Paquetes',
                ),
            ))
            ->add(
                $builder->create('proveedor', 'text', array(
                    'label' => 'Proveedor',
                    'label_attr'=>  array(
//                        'class' =>  'sr-only',
                    ),
                    'attr'      =>  array(
                        'autocomplete'  =>  'off',
                        'class'         =>  'form-control input-lg typeahead',
                        'placeholder'   =>  'Proveedor',
                    ),
                ))
                    ->addModelTransformer($transformerProveedor)
            )
            ->add(
                $builder->create('institucion', 'text', array(
                    'label' => 'Institución',
                    'label_attr'=>  array(
//                        'class' =>  'sr-only',
                    ),
                    'attr'      =>  array(
                        'autocomplete'  =>  'off',
                        'class'         =>  'form-control input-lg typeahead',
                        'placeholder'   =>  'Institución',
                    ),
                ))
                    ->addModelTransformer($transformerInstitucion)
            );
//            ->add('proveedor', 'text', array(
//                'label' => 'Proveedor',
//                'label_attr'=>  array(
////                    'class' =>  'sr-only',
//                ),
//                'attr'      =>  array(
//                    'autocomplete'  =>  'off',
//                    'class'         =>  'form-control input-lg typeahead',
//                    'placeholder'   =>  'Proveedor',
//                ),
//            ))
//            ->add('institucion',  'text', array(
//                'label' => 'Institución',
//                'label_attr'=>  array(
////                    'class' =>  'sr-only',
//                ),
//                'attr'      =>  array(
//                    'autocomplete'  =>  'off',
//                    'class'         =>  'form-control input-lg typeahead',
//                    'placeholder'   =>  'Institución',
//                ),
//            ))
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
            )) 
            ->setRequired(array(
                'em',
            ))
            ->setAllowedTypes(array(
                'em' => 'Doctrine\Common\Persistence\ObjectManager',
            ));
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_medicionesbundle_medicioninstitucion';
    }
}
