<?php

namespace QoS\MedicionesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use QoS\MedicionesBundle\Entity\MedicionProveedor;
use QoS\MedicionesBundle\Form\MedicionProveedorType;

/**
 * MedicionProveedor controller.
 *
 * @Route("/Medicion-Proveedor")
 */
class MedicionProveedorController extends Controller
{

    /**
     * Creates a new MedicionInstitucion entity.
     *
     * @Route("/{id}/Lista-de-{tipo}/", name="medicionproveedor_get_newBar_")
     * @Route("/Lista-de-{tipo}/", name="medicionproveedor_get_newBar")
     * @Method("GET")
     * @Template("QoSMedicionesBundle:MedicionInstitucion:new.html.twig")
     */
    public function getNewBarAction(Request $request, $tipo, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $tipo = strtolower($tipo);
        switch($tipo){
            case 'proveedores':
//                if($id){
//                    $proveedor = $em->getRepository('QoSAdminBundle:Proveedor')->find($id);
//                    $objs = $institucion->getProveedores();
//                }else{
                    $objs = $em->getRepository('QoSAdminBundle:Proveedor')->findAll();
//                }
                break;
//            case 'instituciones':
//                if($id){
//                    $institucion = $em->getRepository('QoSAdminBundle:Proveedor')->find($id);
//                    $objs = $institucion->getInstituciones();
//                }else{
//                    $objs = $em->getRepository('QoSAdminBundle:Institucion')->findAll();
//                }
//                break;
        }
        $datos = array();
        foreach($objs as $obj){
            $datos[] = array(
                'stateCode'=>$obj->getId(),
                'stateName'=>$obj->__tostring(),
                'abreviacion'=>$obj->getAbreviacion(),
            );
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($datos);
    }
    /**
     * Lists all MedicionProveedor entities.
     *
     * @Route("/", name="medicionproveedor")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('QoSMedicionesBundle:MedicionProveedor')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new MedicionProveedor entity.
     *
     * @Route("/", name="medicionproveedor_create")
     * @Route("/{idProveedor}/", name="medicionproveedor_create_proveedor")
     * @Method("POST")
     * @Template("QoSMedicionesBundle:MedicionProveedor:new.html.twig")
     */
    public function createAction(Request $request, $idProveedor = null)
    {
        $entity = new MedicionProveedor();
        $entity->setUsuario($this->getUser());
        if(!is_null($idProveedor)){
            $proveedor = $this->getDoctrine()->getManager()->getRepository('QoSAdminBundle:Proveedor')->find($idProveedor);
            if($proveedor){
                $entity->setProveedor($proveedor);
            }
        }
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

//            return $this->redirect($this->generateUrl('proveedor_show', array('id' => $entity->getProveedor()->getId())));
            return $this->redirect($this->generateUrl('medicionproveedor_show', array('id' => $entity->getId())));
        }

        $data =  array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
        if($request->isXmlHttpRequest()){
            $data = \Symfony\Component\HttpFoundation\JsonResponse::create(array(
                'body'  => $this->renderView('QoSMedicionesBundle:MedicionProveedor:new.html.twig', $data),
                'title' => 'Agregar Medición Esperada por el proveedor'
            ));
        }
        return $data;
    }

    /**
     * Creates a form to create a MedicionProveedor entity.
     *
     * @param MedicionProveedor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MedicionProveedor $entity)
    {
        $datos = array();
        $url = 'medicionproveedor_create';
        if($entity->getProveedor()){
            $datos['idProveedor'] = $entity->getProveedor()->getId();
            $url = 'medicionproveedor_create_proveedor';
        }
        $form = $this->createForm(new MedicionProveedorType(), $entity, array(
            'em' => $this->getDoctrine()->getManager(),
            'action' => $this->generateUrl($url, $datos),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear','attr'      =>  array(
                    'class'         =>  'btn btn-success',
                ),));

        return $form;
    }

    /**
     * Displays a form to create a new MedicionProveedor entity.
     *
     * @Route("/new", name="medicionproveedor_new")
     * @Route("/new/{idProveedor}", name="medicionproveedor_new_proveedor")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request, $idProveedor = null)
    {
        $entity = new MedicionProveedor();
        $entity->setUsuario($this->getUser());
        if(!is_null($idProveedor)){
            $proveedor = $this->getDoctrine()->getManager()->getRepository('QoSAdminBundle:Proveedor')->find($idProveedor);
            if($proveedor){
                $entity->setProveedor($proveedor);
            }
        }
        $form   = $this->createCreateForm($entity);
        $data = array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
        if($request->isXmlHttpRequest()){
            $data = \Symfony\Component\HttpFoundation\JsonResponse::create(array(
                'body'  => $this->renderView('QoSMedicionesBundle:MedicionProveedor:new.html.twig', $data),
                'title' => 'Agregar Medición Esperada por el proveedor'
            ));
        }
        return $data;
    }

    /**
     * Finds and displays a MedicionProveedor entity.
     *
     * @Route("/{id}", name="medicionproveedor_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:MedicionProveedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MedicionProveedor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $data = array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
        if($request->isXmlHttpRequest()){
            $data = \Symfony\Component\HttpFoundation\JsonResponse::create(array(
                'body'  => $this->renderView('QoSMedicionesBundle:MedicionProveedor:show.html.twig', $data),
                'title' => 'Medición del proveedor'
            ));
        }
        return $data;
    }

    /**
     * Displays a form to edit an existing MedicionProveedor entity.
     *
     * @Route("/{id}/edit", name="medicionproveedor_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:MedicionProveedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MedicionProveedor entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a MedicionProveedor entity.
    *
    * @param MedicionProveedor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MedicionProveedor $entity)
    {
        $form = $this->createForm(new MedicionProveedorType(), $entity, array(
            'action' => $this->generateUrl('medicionproveedor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing MedicionProveedor entity.
     *
     * @Route("/{id}", name="medicionproveedor_update")
     * @Method("PUT")
     * @Template("QoSMedicionesBundle:MedicionProveedor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:MedicionProveedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MedicionProveedor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('medicionproveedor_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a MedicionProveedor entity.
     *
     * @Route("/{id}", name="medicionproveedor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QoSMedicionesBundle:MedicionProveedor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MedicionProveedor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('medicionproveedor'));
    }

    /**
     * Creates a form to delete a MedicionProveedor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('medicionproveedor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Borrar'))
            ->getForm()
        ;
    }
}
