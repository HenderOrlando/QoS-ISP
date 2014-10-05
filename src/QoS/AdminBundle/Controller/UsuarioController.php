<?php

namespace QoS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use QoS\AdminBundle\Entity\Usuario;
use QoS\AdminBundle\Form\UsuarioType;

/**
 * Usuario controller.
 *
 * @Route("/Usuario")
 */
class UsuarioController extends Controller
{

    /**
     * Lists all Usuario entities.
     *
     * @Route("/", name="Usuario")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('QoSAdminBundle:Usuario')->findAll();

        $datosGrafico = $this->getDatosGrafico();
        $rowMains = array();
        foreach($datosGrafico as $name => $dt){
            $rowMains[] = array(
                'name'  =>  $name,
                'table' =>  $this->renderView('QoSAdminBundle:Secured:_table.html.twig', array(
                    'theads' => array(
                            array(
                                'tds' => array(
                                    array(
                                        'val' => 'Nombre del Rol'
                                    ),
                                    array(
                                        'val' => 'Número de Usuarios con el Rol'
                                    ),
                                    array(
                                        'val' => 'Usuarios con el Rol'
                                    ),
                                ),
                            ),
                        ),
                    'tbodys' => $this->getTBodys($name),
                    )
                )
            );
        }
        
        $entity = new Usuario();
        $form   = $this->createCreateForm($entity, $this->getUser());
        
        return array(
//            'entities'  =>  $entities,
            'form'          => $form->createView(),
            'rowMains'      => $rowMains,
            'datosGrafico'  => $datosGrafico,
        );
    }
    /**
     * Creates a new Usuario entity.
     *
     * @Route("/", name="Usuario_create")
     * @Method("POST")
     * @Template("QoSAdminBundle:Usuario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Usuario();
        $form = $this->createCreateForm($entity, $this->getUser());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->setSecurePassword($entity);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('Usuario_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Usuario $entity, $user)
    {
        $form = $this->createForm(new UsuarioType($user), $entity, array(
            'action' => $this->generateUrl('Usuario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     * @Route("/new", name="Usuario_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Usuario();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Usuario entity.
     *
     * @Route("/{username}/", name="cuenta")
     * @Route("/{id}/", name="Usuario_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id=null, $username = null)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $context = $this->get('security.context');
        $repo = $this->getRepository();
        
        $text = is_null($id)?$username:$id;
        $entity = $repo->getUserByIdUsername($text);

        if (!$entity) {
            throw $this->createNotFoundException("Usuario \"$username\" no encontrado.");
        }
        $id = $entity->getId();
        $deleteForm = null;
        $editForm = null;
        if($context && $context->isGranted('ROLE_SUPER_ADMIN') || $user->getId() === $entity->getId()){
            $editForm = $this->createEditForm($entity, $user);
            $deleteForm = $this->createDeleteForm($id, $user);
        }

        return array(
            'entity'        =>  $entity,
            'delete_form'   =>  $deleteForm->createView(),
            'form'          =>  $editForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/edit", name="Usuario_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSAdminBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createEditForm($entity, $this->getUser());
        $deleteForm = $this->createDeleteForm($id, $this->getUser());

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Usuario entity.
    *
    * @param Usuario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Usuario $entity, $user)
    {
        $form = $this->createForm(new UsuarioType($user), $entity, array(
            'action' => $this->generateUrl('Usuario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Actualizar',
        ));

        return $form;
    }
    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}", name="Usuario_update")
     * @Method("PUT")
     * @Template("QoSAdminBundle:Usuario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSAdminBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $this->getUser());
        $editForm = $this->createEditForm($entity, $this->getUser());
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('Usuario_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Usuario entity.
     *
     * @Route("/{id}", name="Usuario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id, $this->getUser());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QoSAdminBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('Usuario'));
    }

    /**
     * Creates a form to delete a Usuario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Usuario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'attr'  => array(
                    'class' => 'btn btn-lg btn-danger btn-block border-radius-none',
                ),
                'label' => 'Borrar'
            ))
            ->getForm()
        ;
    }
    
    private function setSecurePassword(&$entity) {
        $entity->setSalt(md5(time()));
        
        $password = $this->getTextEncoder($entity->getPassword(), $entity);
        
        $entity->setPassword($password);
    }
    
    private function getTextEncoder($text, Usuario $usuario = null){
        if(is_null($usuario)){
            $usuario = $this->getUser();
        }
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($usuario);
        return $encoder->encodePassword($text, $usuario->getSalt());
    }
    
    /**
     * getRepository
     * 
     * @return QoS\AdminBundle\Entity\Usuario
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository('QoSAdminBundle:Usuario');
    }

    public function getDatosGrafico($datos = null) {
        $em = $this->getDoctrine()->getManager();
        
        $roles = $em->getRepository('QoSAdminBundle:Rol')->findAll();
        if(is_null($datos)){
            $datos = array();
        }
        $datos['roles-usuarios'] = array();
        foreach($roles as $rol){
            $datos['roles-usuarios']['name'] = 'roles-usuarios';
            $datos['roles-usuarios']['values'][] = array(
                'label' => "Rol ".$rol->getNombre(),
                'value' => $rol->getUsuario()->count(),
            );
            
        }
        return $datos;
    }

    public function getTBodys($name) {
        $em = $this->getDoctrine()->getManager();
        
        $tbodys = array();
        switch($name){
            case 'roles-usuarios':
                $roles = $em->getRepository('QoSAdminBundle:Rol')->findAll();
                foreach($roles as $rol){
                    $usrs = '';
                    foreach($rol->getUsuario() as $usr){
                        $url = $this->generateUrl('cuenta', array('username'=>$usr->getUsername()), true);
                        $email = $usr->getEmail();
                        $usrs = "<a href=\"$url\">$email</a>;";
                    }
                    $tbodys[]['tds'] = array(
                        array(
//                            'val' => $rol->getNombre()
                            'val' => '<a href="'.$this->generateUrl('Rol_show', array('id' => $rol->getId())).'">'.$rol->getNombre().'</a>'
                        ),
                        array(
                            'val' => $rol->getUsuario()->count()
                        ),
                        array(
                            'val' => $usrs
                        ),
                    );

                }
                break;
        }
        return $tbodys;
    }

}
