<?php

namespace QoS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use QoS\AdminBundle\Entity\Institucion;
use QoS\AdminBundle\Form\InstitucionType;

/**
 * Institucion controller.
 *
 * @Route("/Institucion")
 */
class InstitucionController extends Controller
{

    /**
     * Lists all Institucion entities.
     *
     * @Route("/", name="Institucion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->getRepository()->findAll();

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
                                        'val' => 'Nombre de la Institución'
                                    ),
                                    array(
                                        'val' => 'Número total de Mediciones'
                                    ),
                                    array(
                                        'val' => "Mediciones del Servicio en la Institución"
                                    ),
                                ),
                            ),
                        ),
                    'tbodys' => $this->getTBodys($name),
                    )
                )
            );
        }
        
        $entity = new Institucion();
        $form   = $this->createCreateForm($entity, $this->getUser());
        
        return array(
//            'entities'  =>  $entities,
            'form'          => $form->createView(),
            'rowMains'      => $rowMains,
            'datosGrafico'  => $datosGrafico,
        );
    }
    /**
     * Creates a new Institucion entity.
     *
     * @Route("/", name="Institucion_create")
     * @Method("POST")
     * @Template("QoSAdminBundle:Institucion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Institucion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('Institucion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Institucion entity.
     *
     * @param Institucion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Institucion $entity)
    {
        $form = $this->createForm(new InstitucionType(), $entity, array(
            'action' => $this->generateUrl('Institucion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Institucion entity.
     *
     * @Route("/new", name="Institucion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Institucion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Institucion entity.
     *
     * @Route("/{id}", name="Institucion_show")
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
     * Displays a form to edit an existing Institucion entity.
     *
     * @Route("/{id}/edit", name="Institucion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSAdminBundle:Institucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Institucion entity.');
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
    * Creates a form to edit a Institucion entity.
    *
    * @param Institucion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Institucion $entity)
    {
        $form = $this->createForm(new InstitucionType(), $entity, array(
            'action' => $this->generateUrl('Institucion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Institucion entity.
     *
     * @Route("/{id}", name="Institucion_update")
     * @Method("PUT")
     * @Template("QoSAdminBundle:Institucion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSAdminBundle:Institucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Institucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('Institucion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Institucion entity.
     *
     * @Route("/{id}", name="Institucion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QoSAdminBundle:Institucion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Institucion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('Institucion'));
    }

    /**
     * Creates a form to delete a Institucion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Institucion_delete', array('id' => $id)))
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
     * @return QoS\AdminBundle\Entity\Institucion
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository('QoSAdminBundle:Institucion');
    }

    public function getDatosGrafico($datos = null) {
        $em = $this->getDoctrine()->getManager();
        
        $instituciones = $this->getRepository()->findAll();
        if(is_null($datos)){
            $datos = array();
        }
        $datos['mediciones-instituciones'] = array();
        foreach($instituciones as $institucion){
            $datos['mediciones-instituciones']['name'] = 'mediciones-instituciones';
            $datos['mediciones-instituciones']['values'][] = array(
                'label' => $institucion->getNombre(),
                'value' => $institucion->getMediciones()->count(),
            );
            
        }
        return $datos;
    }

    public function getTBodys($name) {
        $em = $this->getDoctrine()->getManager();
        
        $tbodys = array();
        switch($name){
            case 'mediciones-instituciones':
                $instituciones = $this->getRepository()->findAll();
                foreach($instituciones as $institucion){
                    $medicionesInstitucion = '';
                    $medicionesInstitucion = '';
                    foreach($institucion->getMediciones() as $medicionInstitucion){
                        $url = $this->generateUrl('medicioninstitucion_show', array('id'=>$medicionInstitucion->getId()), true);
                        $nombre = $medicionInstitucion->getNombre(true);
                        $medicionesInstitucion = "<a href=\"$url\">$nombre</a>;";
                    }
                    $numMI = $institucion->getMediciones()->count();
                    $pluralMI = $numMI == 1?'ón':'ones';
                    $tbodys[]['tds'] = array(
                        array(
//                            'val' => $institucion->getNombre()
                            'val' => '<a href="'.$this->generateUrl('Institucion_show', array('id' => $institucion->getId())).'">'.$institucion->getNombre().'</a>'
                        ),
                        array(
                            'val' => "$numMI medici$pluralMI en la Institución"
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
