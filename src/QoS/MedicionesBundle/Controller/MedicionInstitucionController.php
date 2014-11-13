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
     * Lista de todas las MedicionInstitucion.
     *
     * @Route("/", name="medicioninstitucion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /* Obtiene el manejador ORM para realizar consultas a la Base de Datos */
        $em = $this->getDoctrine()->getManager();

        /* Consulta en la base de datos y recupera todas las mediciones realizadas en las instituciones */
        $entities = $em->getRepository('QoSMedicionesBundle:MedicionInstitucion')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Crea una nueva MedicionInstitucion.
     *
     * @Route("/", name="medicioninstitucion_create")
     * @Method("POST")
     * @Template("QoSMedicionesBundle:MedicionInstitucion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        /* Crea un nuevo objeto */
        $entity = new MedicionInstitucion();
        /* Crea el formulario con las validaciones necesarias de los datos */
        $form = $this->createCreateForm($entity);
        /* Confronta, Valida y Guarda los datos recibidos y las características del nuevo objeto */
        $form->handleRequest($request);

        /* Pregunta si es válido el formulario */
        if ($form->isValid()) {
            /* Obtiene el manejador ORM para realizar consultas a la Base de Datos */
            $em = $this->getDoctrine()->getManager();
            /* Le dice al manejador que se desea guardar el objeto con los datos encontrados */
            $em->persist($entity);
            /* Le dice al manejador ORM que guarde el objeto en la Base de Datos */
            $em->flush();

            /* Redirecciona */
            return $this->redirect($this->generateUrl('medicioninstitucion_show', array('id' => $entity->getId())));
        }

        /* En caso de no ser válidos, muestra los errores para corregirlos y guardar */
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Crea un formulario para agregar una MedicionInstitucion.
     *
     * @param MedicionInstitucion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MedicionInstitucion $entity)
    {
        /* Crea el formulario */
        $form = $this->createForm(new MedicionInstitucionType(), $entity, array(
            'action' => $this->generateUrl('medicioninstitucion_create'),
            'method' => 'POST',
        ));
        /* Agrega el botón submit */
        $form->add('submit', 'submit', array('label' => 'Tomar Medición'));

        return $form;
    }

    /**
     * Muestra un formulario para crear una nueva MedicionInstitucion.
     *
     * @Route("/new", name="medicioninstitucion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        /* Crea un nuevo objeto */
        $entity = new MedicionInstitucion();
        /* Crea el formulario con las validaciones necesarias de los datos */
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
        /* Crea un nuevo objeto */
        $entity = new MedicionInstitucion();
        /* Crea el formulario con las validaciones necesarias de los datos */
        $form = $this->createForm(new MedirInstitucionType(), $entity, array(
            'em' => $this->getDoctrine()->getManager(),
            'action' => $this->generateUrl('medicioninstitucion_create_newBar'),
            'method' => 'PATCH',
        ));
        /* Agrega el botón submit */
        $form->add('submit', 'submit', array('label' => 'Tomar Medición'));
        /* Confronta, Valida y Guarda los datos recibidos y las características del nuevo objeto */
        $form->handleRequest($request);

        /* Arma la respuesta */
        $json = array(
            'msgs' => array(
                'error' => array(
                        'Formulario no válido',
                    ),
            ),
        );
        /* Pregunta si es válido el formulario */
        if ($form->isValid()) {
            /* Obtiene el manejador ORM para realizar consultas a la Base de Datos */
            $em = $this->getDoctrine()->getManager();
            /* el paquete es válido? */
            if(!$entity->getPaquete()){
                /* Obtenga los paquetes */
                $paquetes = $em->getRepository('QoSMedicionesBundle:Paquete')->findAll();
//                $entity->setPaquete($paquetes->get(array_rand($paquetes->toArray())));
                $i = 0;
                /* elija un número al azar entre 0 - numero de paquetes */
                $rand = intval(rand(0, count($paquetes)-1));
//                $rand = 2;
                $paquete = null;
                /* Búsque el paquete de número $i */
                foreach($paquetes as $paq){
                    if($i === $rand){
                        $paquete = $paq;
                        break;
                    }
                    $i++;
                }
                /* Guarde el paquete que usará para medir */
                $entity->setPaquete($paquete);
            }
            /* Guarde el usuario que está realizando la medición */
            $entity->setUsuario($this->getUser());
            /* Le dice al manejador que se desea guardar el objeto con los datos encontrados */
            $em->persist($entity);
            /* Le dice al manejador ORM que guarde el objeto en la Base de Datos */
            $em->flush();
            /* Arma la respuesta */
            $json = array(
                'msgs' => array(
                    'success' => array(
                        'Datos guardados',
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
        /* Encuentra la medición a realizar */
        $mi = $this->getRepository()->find($id);

        $fileurl = $mi->getPaquete()->getWebPath();
            if(strpos($fileurl,'://')){
                /* El path es real */
                /* Mida tiempo de ping */
                $fileurl = $mi->getPaquete()->getWebPath();
                $info = $this->curlGetFile($fileurl);
                $mi->setLengthDownload(0);
                $mi->setLengthUpload(0);
                $mi->setSizeDownload(0);
                $mi->setSizeUpload(0);
                $mi->setTimeConnect(0);
                $mi->setTimeNameLookup(0);
                $mi->setTimePreTransfer(0);
                $mi->setTimeRedirect(0);
                $mi->setTimeStartTransfer(0);
                $mi->setTimeTotal($info['total_time']);
                $mi->setSpeedDownload(0);
                $mi->setSpeedUpload(0);
            }else{
                /* Cambia por path real (URL->http://dominio/cargar_bytes.php) */
                /* Mida velocidad */
                $fileurl = 'http://'.$request->getHttpHost().$request->getBasePath().'/'.$fileurl;
                $info = $this->curlGetFile($fileurl);
                $info['download_content_length'] = $info['download_content_length'] > 0?$info['download_content_length']:0;
                $info['upload_content_length'] = $info['upload_content_length'] > 0?$info['upload_content_length']:0;
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
                $mi->setSpeedDownload($info['download_content_length']/8/$mi->getTimeTotal());
                $mi->setSpeedUpload($info['upload_content_length']/8/$mi->getTimeTotal());
            }
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
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt( $curl, CURLOPT_TIMEOUT, 55);
        
        set_time_limit(60);
        //curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
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
            case 'usuarios':
                $objs = $em->getRepository('QoSAdminBundle:Usuario')->findAll();
                break;
            default:
                $instituciones = $em->getRepository('QoSAdminBundle:Institucion')->findAll();
                $proveedores = $em->getRepository('QoSAdminBundle:Proveedor')->findAll();
                $usuarios = $em->getRepository('QoSAdminBundle:Usuario')->findAll();
                $objs = new \Doctrine\Common\Collections\ArrayCollection(array_merge($instituciones->toArray(), $proveedores->toArray(), $usuarios->toArray()));
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
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QoSMedicionesBundle:MedicionInstitucion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MedicionInstitucion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $data = array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
        if($request->isXmlHttpRequest()){
            return \Symfony\Component\HttpFoundation\JsonResponse::create(array(
                'title' => 'Medición del '.$entity->getFechaCreado('Y-m-d H:i:s'),
                'body' => $this->renderView('QoSMedicionesBundle:MedicionInstitucion:show.html.twig', $data),
            ));
        }
        return $data;
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
        $entity = new MedicionInstitucion();
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

        if($request->isXmlHttpRequest()){
            return \Symfony\Component\HttpFoundation\JsonResponse::create(array(
                'title' => 'Medición Eliminada',
                'body' => 'La '.$entity->getNombre().' en la fecha (año-mes-dia) '.$entity->getFechaCreado('Y-m-d').' fué eliminada exitosamente.',
            ));
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
            ->add('submit', 'submit', array(
                'label' => 'Borrar', 
                'attr' => array(
                        'class' => 'btn btn-warning'
                    )
            ))
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
