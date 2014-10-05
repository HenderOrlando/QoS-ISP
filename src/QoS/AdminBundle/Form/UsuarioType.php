<?php

namespace QoS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    private $user;
    
    public function __construct(\QoS\AdminBundle\Entity\Usuario $user = null){
        $this->user = $user;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $usuario = $builder->getData();
        
        $builder
            ->add('docId', null, array(
                'label'     =>  'Documento de Identidad',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Documento de Identidad',
                ),
            ))
            ->add('username', null, array(
                'label'     =>  'Nombre del Usuario',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Nombre del Usuario',
                ),
            ))
            ->add('email', null, array(
                'label'     =>  'Email',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Email',
                ),
            ))
        ;
        if($this->user && ($this->user->getRol()->getCanonical() === 'super_admin' || $usuario->getId() === $this->user->getId())){
            $builder->add('clave', 'password', array(
                'label'     =>  'Password/Clave',
                'label_attr'=>  array(
                    'class' =>  'sr-only',
                ),
                'attr'      =>  array(
                    'class'         =>  'form-control input-lg',
                    'placeholder'   =>  'Password/Clave',
                ),
            ));
            if($this->user->getRol()->getCanonical() === 'super_admin'){
//            if($usuario instanceof \QoS\AdminBundle\Entity\Usuario && (($usuario->getRol() && $usuario->getRol()->getCanonical() === 'super_admin') || $usuario->getId())){
                $builder->add('rol', null, array(
                    'label'     =>  'Rol del Usuario',
                    'label_attr'=>  array(
//                    'class' =>  'sr-only',
                    ),
                    'attr'      =>  array(
                        'class'         =>  'form-control input-lg',
                        'placeholder'   =>  'Rol',
                    ),
                ));
//            }
            }
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QoS\AdminBundle\Entity\Usuario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qos_adminbundle_usuario';
    }
}
