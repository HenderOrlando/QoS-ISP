<?php

namespace QoS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use QoS\AdminBundle\Entity\Proveedor;
use QoS\AdminBundle\Form\ProveedorType;

/**
 * Proveedor controller.
 *
 * @Route("/Proveedor")
 */
class ProveedorController extends Controller
{

    /**
     * Lists all Proveedor entities.
     *
     * @Route("/", name="Proveedor")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('QoSAdminBundle:Proveedor')->findAll();

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
                                        'val' => 'Nombre del Proveedor'
                                    ),
                                    array(
                                        'val' => 'Número total de Mediciones'
                                    ),
                                    array(
                                        'val' => "Mediciones del Proveedor (Medición Esperada) <a href=\""
                                        .$this->generateUrl('medicionproveedor_new')
                                        ."\" class=\"label label-success\" title=\"Agregar Medición Esperada del Servicio del Proveedor\">Agregar</a>"
                                    ),
                                    array(
                                        'val' => "Mediciones al Proveedor (en Instituciones) <a href=\""
                                        .$this->generateUrl('medicioninstitucion_new')
                                        ."\" class=\"label label-success\" title=\"Hacer Medición a Servicio del Proveedor\">Agregar</a>"
                                    ),
                                ),
                            ),
                        ),
                    'tbodys' => $this->getTBodys($name),
                    )
                )
            );
        }
        
        $entity = new Proveedor();
        $form   = $this->createCreateForm($entity, $this->getUser());
        
        return array(
//            'entities'  =>  $entities,
            'form'          => $form->createView(),
            'rowMains'      => $rowMains,
            'datosGrafico'  => $datosGrafico,
        );
    }
    /**
     * Creates a new Proveedor entity.
     *
     * @Route("/", name="Proveedor_create")
     * @Method("POST")
     * @Template("QoSAdminBundle:Proveedor:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Proveedor();
        $form = $this->createCreateForm($entity, $this->getUser());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('Proveedor_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Proveedor entity.
     *
     * @param Proveedor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Proveedor $entity, $user)
    {
        $form = $this->createForm(new ProveedorType($user), $entity, array(
            'action' => $this->generateUrl('Proveedor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Proveedor entity.
     *
     * @Route("/new", name="Proveedor_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Proveedor();
        $form   = $this->createCreateForm($entity, $this->getUser());

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Proveedor entity.
     *
     * @Route("/{id}", name="Proveedor_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $context = $this->get('security.context');

        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("Paquete \"$id\" no encontrado.");
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
     * Displays a form to edit an existing Proveedor entity.
     *
     * @Route("/{id}/edit", name="Proveedor_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSAdminBundle:Proveedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proveedor entity.');
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
    * Creates a form to edit a Proveedor entity.
    *
    * @param Proveedor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Proveedor $entity, $user)
    {
        $form = $this->createForm(new ProveedorType($user), $entity, array(
            'action' => $this->generateUrl('Proveedor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Proveedor entity.
     *
     * @Route("/{id}", name="Proveedor_update")
     * @Method("PUT")
     * @Template("QoSAdminBundle:Proveedor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSAdminBundle:Proveedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proveedor entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $this->getUser());
        $editForm = $this->createEditForm($entity, $this->getUser());
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('Proveedor_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Proveedor entity.
     *
     * @Route("/{id}", name="Proveedor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id, $this->getUser());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QoSAdminBundle:Proveedor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Proveedor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('Proveedor'));
    }

    /**
     * Creates a form to delete a Proveedor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Proveedor_delete', array('id' => $id)))
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
    
    /**
     * getRepository
     * 
     * @return QoS\AdminBundle\Entity\Proveedor
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository('QoSAdminBundle:Proveedor');
    }

    public function getDatosGrafico($datos = null) {
        $em = $this->getDoctrine()->getManager();
        
        $proveedores = $this->getRepository()->findAll();
        if(is_null($datos)){
            $datos = array();
        }
        $datos['mediciones-proveedores'] = array();
        foreach($proveedores as $proveedor){
            $datos['mediciones-proveedores']['name'] = 'mediciones-proveedores';
            $datos['mediciones-proveedores']['values'][] = array(
                'label' => $proveedor->getNombre(),
                'value' => $proveedor->getMedicionesInstitucion()->count(),
            );
            
        }
        return $datos;
    }

    public function getTBodys($name) {
        $em = $this->getDoctrine()->getManager();
        
        $tbodys = array();
        switch($name){
            case 'mediciones-proveedores':
                $proveedores = $this->getRepository()->findAll();
                foreach($proveedores as $proveedor){
                    $medicionesProveedor = '';
                    $medicionesInstitucion = '';
                    foreach($proveedor->getMedicionesInstitucion() as $medicionInstitucion){
                        $url = $this->generateUrl('medicioninstitucion_show', array('id'=>$medicionInstitucion->getId()), true);
                        $nombre = $medicionInstitucion->getNombre(true);
                        $medicionesInstitucion .= "<a class=\"label label-default\" href=\"$url\">$nombre</a>; ";
                    }
                    foreach($proveedor->getMedicionesProveedor() as $medicionProveedor){
                        $url = $this->generateUrl('medicionproveedor_show', array('id'=>$medicionProveedor->getId()), true);
                        $nombre = $medicionProveedor->getNombre(true);
                        $medicionesProveedor .= "<a class=\"label label-default\" href=\"$url\">$nombre</a>; ";
                    }
                    $numMP = $proveedor->getMedicionesProveedor()->count();
                    $pluralMP = $numMP == 1?'ón':'ones';
                    $numMI = $proveedor->getMedicionesInstitucion()->count();
                    $pluralMI = $numMI == 1?'ón':'ones';
                    $tbodys[]['tds'] = array(
                        array(
//                            'val' => $proveedor->getNombre()
                            'val' => '<a href="'.$this->generateUrl('Proveedor_show', array('id' => $proveedor->getId())).'">'.$proveedor->getNombre().'</a>'
                        ),
                        array(
                            'val' => "$numMI medici$pluralMI en Instituciones <br/> $numMP medici$pluralMP base del servicio del proveedor"
                        ),
                        array(
                            'val' => $medicionesProveedor
                        ),
                        array(
                            'val' => $medicionesInstitucion
                        ),
                    );

                }
                break;
        }
        return $tbodys;
    }
}
