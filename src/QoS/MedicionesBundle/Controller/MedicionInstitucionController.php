<?php

namespace QoS\MedicionesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use QoS\MedicionesBundle\Entity\MedicionInstitucion;
use QoS\MedicionesBundle\Form\MedicionInstitucionType;
use QoS\MedicionesBundle\Form\MedirInstitucionType;

/**
 * MedicionInstitucion controller.
 *
 * @Route("/Medicion-Institucion")
 */
class MedicionInstitucionController extends Controller
{

    /**
     * Lists all MedicionInstitucion entities.
     *
     * @Route("/", name="medicioninstitucion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('QoSMedicionesBundle:MedicionInstitucion')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new MedicionInstitucion entity.
     *
     * @Route("/", name="medicioninstitucion_create")
     * @Method("POST")
     * @Template("QoSMedicionesBundle:MedicionInstitucion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MedicionInstitucion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('medicioninstitucion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a MedicionInstitucion entity.
     *
     * @param MedicionInstitucion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MedicionInstitucion $entity)
    {
        $form = $this->createForm(new MedicionInstitucionType(), $entity, array(
            'action' => $this->generateUrl('medicioninstitucion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Tomar Medición'));

        return $form;
    }

    /**
     * Displays a form to create a new MedicionInstitucion entity.
     *
     * @Route("/new", name="medicioninstitucion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MedicionInstitucion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new MedicionInstitucion entity.
     *
     * @Route("/", name="medicioninstitucion_create_newBar")
     * @Method("PATCH")
     * @Template("QoSMedicionesBundle:MedicionInstitucion:new.html.twig")
     */
    public function createNewBarAction(Request $request)
    {
        $entity = new MedicionInstitucion();
        $form = $this->createForm(new MedirInstitucionType(), $entity, array(
            'em' => $this->getDoctrine()->getManager(),
            'action' => $this->generateUrl('medicioninstitucion_create_newBar'),
            'method' => 'PATCH',
        ));

        $form->add('submit', 'submit', array('label' => 'Tomar Medición'));
        $form->handleRequest($request);

        $json = array(
            'msgs' => array(
                'error' => array(
                        'Formulario no válido',
                    ),
            ),
        );
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$entity->getPaquete()){
                $paquetes = $em->getRepository('QoSMedicionesBundle:Paquete')->findAll();
//                $entity->setPaquete($paquetes->get(array_rand($paquetes->toArray())));
                $i = 0;
                $rand = intval(rand(0, count($paquetes)-1));
//                $rand = 2;
                $paquete = null;
                foreach($paquetes as $paq){
                    if($i === $rand){
                        $paquete = $paq;
                        break;
                    }
                    $i++;
                }
                $entity->setPaquete($paquete);
            }
            $entity->setUsuario($this->getUser());
            $em->persist($entity);
            $em->flush();
            $json = array(
                'msgs' => array(
                    'success' => array(
                        'Formulario válido',
                    ),
                ),
                'form' => $this->renderView('QoSAdminBundle:Secured:_form.html.twig', array('form'=>$form->createView())),
                'nombre' => $entity->getNombre(),
                'urlFile' => $this->generateUrl('medicioninstitucion_get_medir_file',array('id'=>$entity->getId())),
                'nombreCorto' => $entity->getNombre(true),
            );
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($json);
    }
    
    /**
     * Creates a new MedicionInstitucion entity.
     *
     * @Route("/Archivo/{id}/", name="medicioninstitucion_get_medir_file")
     * @Method("Post")
     * @Template()
     */
    public function getMedirFileAction(Request $request, $id)
    {
        $mi = $this->getRepository()->find($id);

        /* cambiar por path real (URL) http://tudominio/cargar_bytes.php)*/ 
        $fileurl = $mi->getPaquete()->getWebPath();
        if(strpos($fileurl,'://')){
            $fileurl = $mi->getPaquete()->getWebPath();
        }else{
            $fileurl = 'http://'.$request->getHttpHost().$request->getBasePath().'/'.$fileurl;
        }
        $info = $this->curlGetFile($fileurl); 
        $mi->setLengthDownload($info['download_content_length']);
        $mi->setLengthUpload($info['upload_content_length']);
        $mi->setSizeDownload($info['size_download']);
        $mi->setSizeUpload($info['size_upload']);
        $mi->setTimeConnect($info['connect_time']);
        $mi->setTimeNameLookup($info['namelookup_time']);
        $mi->setTimePreTransfer($info['pretransfer_time']);
        $mi->setTimeRedirect($info['redirect_time']);
        $mi->setTimeStartTransfer($info['starttransfer_time']);
        $mi->setTimeTotal($info['total_time']+$info['starttransfer_time']+$info['redirect_time']+$info['pretransfer_time']+$info['namelookup_time']+$info['connect_time']);
        $mi->setSpeedDownload($info['download_content_length']/$mi->getTimeTotal());
        $mi->setSpeedUpload($info['upload_content_length']/$mi->getTimeTotal());
        $em = $this->getDoctrine()->getManager();
        $em->persist($mi);
        $em->flush();
        
        return new \Symfony\Component\HttpFoundation\JsonResponse($mi->json(true));
    }
    
    private function curlGetFile($url) {
        // Assume failure.
        $result = -1;

        $curl = curl_init( $url );

        // Issue a HEAD request and follow any redirects.
        curl_setopt( $curl, CURLOPT_NOBODY, true );
        curl_setopt( $curl, CURLOPT_HEADER, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
//        curl_setopt( $curl, CURLOPT_USERAGENT, get_user_agent_string() );

        $data = curl_exec( $curl );
        $info = curl_getinfo( $curl );
        curl_close( $curl );
        return $info;
    }
    /**
     * Creates a new MedicionInstitucion entity.
     *
     * @Route("/Lista-de-{tipo}/{id}/", name="medicioninstitucion_get_newBar_")
     * @Route("/Lista-de-{tipo}/", name="medicioninstitucion_get_newBar")
     * @Method("GET")
     * @Template("QoSMedicionesBundle:MedicionInstitucion:new.html.twig")
     */
    public function getNewBarAction(Request $request, $tipo, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $tipo = strtolower($tipo);
        switch($tipo){
            case 'proveedores':
                if($id){
                    $institucion = $em->getRepository('QoSAdminBundle:Institucion')->find($id);
                    $objs = $institucion->getProveedores();
                }else{
                    $objs = $em->getRepository('QoSAdminBundle:Proveedor')->findAll();
                }
                break;
            case 'instituciones':
                if($id){
                    $institucion = $em->getRepository('QoSAdminBundle:Proveedor')->find($id);
                    $objs = $institucion->getInstituciones();
                }else{
                    $objs = $em->getRepository('QoSAdminBundle:Institucion')->findAll();
                }
                break;
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
     * Displays a form to create a new MedicionInstitucion entity.
     *
     * @Route("/sidebar/", name="medicioninstitucion_new_sidebar")
     * @Method("GET")
     * @Template()
     */
    public function newSidebarAction()
    {
        $entity = new MedicionInstitucion();
        $form = $this->createForm(new MedirInstitucionType(), $entity, array(
            'em' => $this->getDoctrine()->getManager(),
            'action' => $this->generateUrl('medicioninstitucion_create_newBar'),
            'method' => 'POST',
            'attr' => array(
                'class' =>  'ajax-form'
            ),
        ));

        $form->add('submit', 'submit', array(
            'attr' => array(
                'data-ajax' =>  true,
            ),
            'label' => 'Tomar Medición',
        ));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a MedicionInstitucion entity.
     *
     * @Route("/{id}", name="medicioninstitucion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:MedicionInstitucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MedicionInstitucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MedicionInstitucion entity.
     *
     * @Route("/{id}/edit", name="medicioninstitucion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:MedicionInstitucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MedicionInstitucion entity.');
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
    * Creates a form to edit a MedicionInstitucion entity.
    *
    * @param MedicionInstitucion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MedicionInstitucion $entity)
    {
        $form = $this->createForm(new MedicionInstitucionType(), $entity, array(
            'action' => $this->generateUrl('medicioninstitucion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing MedicionInstitucion entity.
     *
     * @Route("/{id}", name="medicioninstitucion_update")
     * @Method("PUT")
     * @Template("QoSMedicionesBundle:MedicionInstitucion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:MedicionInstitucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MedicionInstitucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('medicioninstitucion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a MedicionInstitucion entity.
     *
     * @Route("/{id}", name="medicioninstitucion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QoSMedicionesBundle:MedicionInstitucion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MedicionInstitucion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('medicioninstitucion'));
    }

    /**
     * Creates a form to delete a MedicionInstitucion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('medicioninstitucion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Borrar'))
            ->getForm()
        ;
    }
    
    /**
     * 
     * @return \QoS\MedicionesBundle\Entity\MedicionInstitucion
     */
    private function getRepository(){
        return $this->getDoctrine()->getManager()->getRepository('QoSMedicionesBundle:MedicionInstitucion');
    }
}
