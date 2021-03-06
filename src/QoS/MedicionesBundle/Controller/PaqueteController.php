<?php

namespace QoS\MedicionesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use QoS\MedicionesBundle\Entity\Paquete;
use QoS\MedicionesBundle\Form\PaqueteType;

/**
 * Paquete controller.
 *
 * @Route("/Paquete")
 */
class PaqueteController extends Controller
{

    /**
     * Lists all Paquete entities.
     *
     * @Route("/", name="Paquete")
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
                                        'val' => 'Nombre del Paquete'
                                    ),
                                    array(
                                        'val' => 'Total de Mediciones'
                                    ),
                                    array(
                                        'val' => "Mediciones con el Paquete"
                                    ),
                                ),
                            ),
                        ),
                    'tbodys' => $this->getTBodys($name),
                    )
                )
            );
        }
        
        $entity = new Paquete();
        $form   = $this->createCreateForm($entity, $this->getUser());
        
        return array(
//            'entities'  =>  $entities,
            'form'          => $form->createView(),
            'rowMains'      => $rowMains,
            'datosGrafico'  => $datosGrafico,
        );
    }
    /**
     * Creates a new Paquete entity.
     *
     * @Route("/", name="Paquete_create")
     * @Method("POST")
     * @Template("QoSMedicionesBundle:Paquete:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Paquete();
        $form = $this->createCreateForm($entity, $this->getUser());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('Paquete_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Paquete entity.
     *
     * @param Paquete $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Paquete $entity, $user)
    {
        $form = $this->createForm(new PaqueteType($user), $entity, array(
            'action' => $this->generateUrl('Paquete_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Paquete entity.
     *
     * @Route("/new", name="Paquete_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Paquete();
        $form   = $this->createCreateForm($entity, $this->getUser());

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Paquete entity.
     *
     * @Route("/{id}", name="Paquete_show")
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
        $datosGrafico = $this->getDatosGrafico(null, $entity);
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
                                        'val' => 'Total de Mediciones'
                                    ),
                                    array(
                                        'val' => "Mediciones con el Paquete"
                                    ),
                                ),
                            ),
                        ),
                    'tbodys' => $this->getTBodys('uso-paquete', $entity),
                    )
                )
            );
        }
        return array(
            'entity'        =>  $entity,
            'delete_form'   =>  $deleteForm->createView(),
            'form'          =>  $editForm->createView(),
            'rowMains'      => $rowMains,
            'datosGrafico'  => $datosGrafico,
        );
    }

    /**
     * Displays a form to edit an existing Paquete entity.
     *
     * @Route("/{id}/edit", name="Paquete_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:Paquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paquete entity.');
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
    * Creates a form to edit a Paquete entity.
    *
    * @param Paquete $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Paquete $entity, $user)
    {
        $form = $this->createForm(new PaqueteType(), $entity, array(
            'action' => $this->generateUrl('Paquete_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Paquete entity.
     *
     * @Route("/{id}", name="Paquete_update")
     * @Method("PUT")
     * @Template("QoSMedicionesBundle:Paquete:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:Paquete')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paquete entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $this->getUser());
        $editForm = $this->createEditForm($entity, $this->getUser());
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('Paquete_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Paquete entity.
     *
     * @Route("/{id}", name="Paquete_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QoSMedicionesBundle:Paquete')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Paquete entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('Paquete'));
    }

    /**
     * Creates a form to delete a Paquete entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Paquete_delete', array('id' => $id)))
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
     * @return QoS\MedicionesBundle\Entity\Paquete
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository('QoSMedicionesBundle:Paquete');
    }
    
    public function getDatosGrafico($datos = null, Paquete $paquete = null) {
        $em = $this->getDoctrine()->getManager();
        
        if(is_null($datos)){
            $datos = array();
        }
        $countAll = $em->getRepository('QoSMedicionesBundle:MedicionInstitucion')
                ->createQueryBuilder('mi')
                ->select('COUNT(mi.id)')
                ->getQuery()
                ->getSingleScalarResult();
        if(is_null($paquete)){
            $datos['Uso-paquetes'] = array();
            $paquetes = $this->getRepository()->findAll();
            foreach($paquetes as $paquete){
                $datos['Uso-paquetes']['name'] = 'Uso-paquetes';
                $count = $paquete->getConfiguracion()->count();
                $datos['Uso-paquetes']['values'][] = array(
                    'label' => $paquete->getNombre().' ('.(round(($count/$countAll)*100,2)).'%)',
                    'value' => $count,
                );
            }
        }else{
            $datos['Uso-'.$paquete->getCanonical()] = array();
            $datos['Uso-'.$paquete->getCanonical()]['name'] = 'Uso-'.$paquete->getCanonical();
            $count = $paquete->getConfiguracion()->count();
            $datos['Uso-'.$paquete->getCanonical()]['values'][] = array(
                'label' => $paquete->getNombre().' ('.(round(($count/$countAll)*100,2)).'%)',
                'value' => $count,
            );
        }
        return $datos;
    }
    
    public function getTBodys($name, $paquete = null) {
        $em = $this->getDoctrine()->getManager();
        $tbodys = array();
        switch($name){
            case 'uso-paquete':
            case 'Uso-paquetes':
                if(is_null($paquete)){
                    foreach($this->getRepository()->findAll() as $paquete){
                        $mediciones = '';
                        foreach($paquete->getConfiguracion() as $medicion){
                            $url = $this->generateUrl('medicioninstitucion_show', array('id'=>$medicion->getId()), true);
                            $nombre = $medicion->getNombre();
                            $fecha = $medicion->getFechaCreado('Y-m-d Y:i');
                            $mediciones .= "<a class=\"label label-default open-modal\" href=\"$url\">$nombre ($fecha)</a> ";
                        }
                        $tbodys[]['tds'] = array(
                            array(
                                'val' => '<a href="'.$this->generateUrl('Paquete_show', array('id' => $paquete->getId())).'">'.$paquete->getNombre().'</a>'
                            ),
                            array(
                                'val' => $paquete->getConfiguracion()->count()
                            ),
                            array(
                                'val' => $mediciones
                            ),
                        );
                    }
                }else{
                    $mediciones = '';
                    foreach($paquete->getConfiguracion() as $medicion){
                        $url = $this->generateUrl('medicioninstitucion_show', array('id'=>$medicion->getId()), true);
                        $nombre = $medicion->getNombre();
                        $mediciones = "<a href=\"$url\">$nombre</a>;";
                    }
                    $tbodys[]['tds'] = array(
                        array(
                            'val' => '<a href="'.$this->generateUrl('Paquete_show', array('id' => $paquete->getId())).'">'.$paquete->getNombre().'</a>'
                        ),
                        array(
                            'val' => $paquete->getConfiguracion()->count()
                        ),
                        array(
                            'val' => $mediciones
                        ),
                    );
                }
                break;
        }
        return $tbodys;
    }
}
