<?php

namespace QoS\MedicionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MedicionProveedorType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformerProveedor = new DataTransformer\ProveedorTransformer($options['em']);
        $transformerUsuario= new DataTransformer\UsuarioTransformer($options['em']);
        $builder
//            ->add('actual', null, array(
//                'label' => 'Usar como actual?',
//                'required' => false,
//                'label_attr'=>  array(
////                    'class' =>  'sr-only',
//                ),
//                'attr'      =>  array(
//                    'class'         =>  '',
//                    'placeholder'   =>  'Usar como actual?',
//                ),
//            ))
            ->add('speedUpload', null, array(
                'label' => 'Velocidad de Subida (bytes)',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Velocidad de Subida (bytes)',
                ),
            ))
//            ->add('timeConnect', null, array(
//                'label' => 'Tiempo de Subida (bytes)',
//                'label_attr'=>  array(
//                    'class' =>  'sr-only',
//                ),
//                'attr'      =>  array(
//                    'class'         =>  'form-control input-lg',
//                    'placeholder'   =>  'Tiempo de Subida (bytes)',
//                ),
//            ))
            ->add('speedDownload', null, array(
                'label' => 'Velocidad de Descarga (bytes)',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Velocidad de Descarga (bytes)',
                ),
            ))
//            ->add('timeTotal', null, array(
//                'label' => 'Tiempo de Descarga (seg)',
//                'label_attr'=>  array(
//                    'class' =>  'sr-only',
//                ),
//                'attr'      =>  array(
//                    'class'         =>  'form-control input-lg',
//                    'placeholder'   =>  'Tiempo de Descarga (seg)',
//                ),
//            ))
//            ->add('proveedor', new \QoS\AdminBundle\Form\ProveedorType(true))
//            ->add('proveedor', null, array(
//                'label' => 'Proveedor de la Medición',
//                'label_attr'=>  array(
//                    'class' =>  'sr-only',
//                ),
//                'attr'      =>  array(
//                    'class'         =>  'form-control input-lg',
//                    'placeholder'   =>  'Proveedor de la Medición',
//                ),
//            ))
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
                $builder->create('usuario', 'hidden', array(
                    'label' => 'Usuario que agrega la Medición',
                    'label_attr'=>  array(
                        'class' =>  'sr-only',
                    ),
                    'attr'      =>  array(
                        'readonly'      =>  true,
    //                    'disabled'      =>  true,
                        'class'         =>  'form-control input-lg',
                        'placeholder'   =>  'Usuario que agrega la Medición',
                    ),
                ))
                    ->addModelTransformer($transformerUsuario)
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QoS\MedicionesBundle\Entity\MedicionProveedor'
        ))
        ->setRequired(array(
            'em',
        ))
        ->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_medicionesbundle_medicionproveedor';
    }
}
