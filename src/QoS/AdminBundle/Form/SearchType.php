<?php

namespace QoS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', new \Symfony\Component\Form\Extension\Core\Type\SearchType(), array(
                'label' => 'Buscar...',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control navbar-form navbar-right typeahead',
                    'placeholder'   =>  'Buscar...',
                ),
            ))
//            ->add('submit', new \Symfony\Component\Form\Extension\Core\Type\SearchType(), array(
//                'label' => 'Buscar',
//                'label_attr'=>  array(
//                    'class' =>  'sr-only',
//                ),
//                'attr'      =>  array(
//                    'class'         =>  'btn btn-lg btn-primary',
//                    'placeholder'   =>  'Buscar',
//                ),
//            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
//            'data_class' => 'QoS\AdminBundle\Entity\Rol'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_search';
    }
}
