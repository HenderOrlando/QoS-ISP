<?php

namespace QoS\AdminBundle\Controller;

use QoS\AdminBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/")
 */
class SecuredController extends Controller
{
    /**
     * @Route("/login/", name="login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        if($this->getUser()){
            return $this->redirect($this->generateUrl('index'));
        }
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return array(
            'last_username' => $lastUsername,
            'error'         => $error,
        );
    }

    /**
     * @Route("/usr-security_check/", name="security_check")
     * 
     */
    public function loginCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/usr-logout/", name="logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/", name="_presentacion"),
     * @Route("/Presentacion/", name="presentacion")
     * @Template()
     */
    public function presentacionAction(Request $request)
    {
        if($this->getUser()){
            return $this->redirect($this->generateUrl('index'));
        }
        return array();
    }

    /**
     * @Route("/usuario/", name="index"),
     * @Route("/usuario-{name}/", name="index_name")
     * @Template("QoSAdminBundle:Secured:index.html.twig")
     */
    public function indexAction(Request $request, $name = false)
    {
        return $this->iniciaSesion('user', $request, $name);
    }

    /**
     * @Route("/usuario-admin/{name}/", name="index_admin")
     * @Template("QoSAdminBundle:Secured:index.html.twig")
     */
    public function indexAdminAction(Request $request, $name = false)
    {
        return $this->iniciaSesion('admin', $request, $name);
    }

    /**
     * @Route("/usuario-super/{name}/", name="index_super_admin")
     * @Template("QoSAdminBundle:Secured:index.html.twig")
     */
    public function indexSuperAdminAction(Request $request, $name = false)
    {
        return $this->iniciaSesion('super', $request, $name);
    }
    /**
     * @Route("/usuario-{name}/find/", name="find")
     * @Template()
     */
    public function findAction(Request $request, $name = false)
    {
        $name = $this->getNameUrl($request, $name);
        $form = $this->createForm(new SearchType(), null, array(
            'action' => $this->generateUrl('find', array('name' => $name)),
            'method' => 'POST',
        ));
        
        $form->handleRequest($request);

        $rta = array(
            'form'  =>  $form->createView(),
        );
        if ($form->isValid()) {
            $data = $form->getData();
            $rta['textToSearch'] = $data['search'];
            $rta['paquetes'] = new ArrayCollection();
            $rta['usuarios'] = new ArrayCollection();
            $rta['mediciones'] = new ArrayCollection();
            $rta['proveedores'] = new ArrayCollection();
            $rta['instituciones'] = new ArrayCollection();
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($entity);
//            $em->flush();
            return $this->render('QoSAdminBundle:Secured:results.html.twig', $rta);
//            return $this->redirect($this->generateUrl('Usuario_show', array('id' => $entity->getId())));
        }
        return $rta;
    }
    
    function iniciaSesion($pag, Request $request, $name = false, $array = array()){
        $security = $this->get('security.context');
        $name = $this->getNameUrl($request, $name);
        $datosGrafico = $this->getDatosGrafico();
        $rowMains = array();
        foreach($datosGrafico as $name => $dt){
            $rowMains[] = array(
                'name'  =>  $name,
                'table' =>  $this->renderView('QoSAdminBundle:Secured:_table.html.twig', array(
                    'theads' => $this->getTDs($name),
                    'tbodys' => $this->getTBodys($name),
                    )
                )
            );
        }
        $rta = array_merge($array, array(
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'rowMains'      => $rowMains,
            'datosGrafico'  => $datosGrafico,
            'name'      => $name,
        ));
//        if($security->isGranted('ROLE_SUPER_ADMIN') && $pag !== 'super'){
//            $rta = $this->redirect($this->generateUrl('index_super_admin'));
//        }elseif($security->isGranted('ROLE_ADMIN') && $pag !== 'admin'){
//            $rta = $this->redirect($this->generateUrl('index_admin'));
//        }elseif($security->isGranted('ROLE_USER') && $pag !== 'usr'){
//            $rta = $this->redirect($this->generateUrl('index'));
//        }else{
//            $rta = $this->redirect($this->generateUrl('login'));
//        }
        return $rta;
    }
    
    public function getDatosGrafico($datos = null) {
        $em = $this->getDoctrine()->getManager();
        
        $instituciones = $em->getRepository('QoSAdminBundle:Institucion')->findAll();
        if(is_null($datos)){
            $datos = array();
        }
        $datos['mediciones-ISP-instituciones'] = array();
        foreach($instituciones as $institucion){
            $datos['mediciones-ISP-instituciones']['name'] = 'mediciones-ISP-instituciones';
            foreach($institucion->getProveedores() as $proveedor){
                $datos['mediciones-ISP-instituciones']['values'][] = array(
                    'label' => $proveedor->getAbreviacion().'-'.$institucion->getAbreviacion(),
                    'value' => $institucion->getPromedioTotal($proveedor, false),
                );
            }
        }
        $proveedores = $em->getRepository('QoSAdminBundle:Proveedor')->findAll();
        $datos['velocidad-descarga-proveedores'] = array();
        foreach($proveedores as $proveedor){
            $datos['velocidad-descarga-proveedores']['name'] = 'velocidad-descarga-proveedores';
            $datos['velocidad-descarga-proveedores']['values'][] = array(
                'label' => $proveedor->getNombre().' ('.$proveedor->getAbreviacion().')',
                'value' => $proveedor->getPromedioDownload(null, false),
            );
        }
        return $datos;
    }
    
    public function getTDs($name) {
        $em = $this->getDoctrine()->getManager();
        
        $tds = array();
        switch($name){
            case 'mediciones-ISP-instituciones':
                $tds = array(
                    array(
                        'tds' => array(
                            array(
                                'val' => 'Institución'
                            ),
                            array(
                                'val' => 'Proveedor'
                            ),
                            array(
                                'val' => 'No. Mediciones - Velocidad Promedio'
                            ),
                        ),
                    ),
                );
                break;
            case 'velocidad-descarga-proveedores':
                $tds = array(
                    array(
                        'tds' => array(
                            array(
                                'val' => 'Proveedor'
                            ),
                            array(
                                'val' => 'Total Mediciones en Instituciones'
                            ),
                            array(
                                'val' => 'Velocidad Promedio'
                            ),
                        ),
                    ),
                );
                break;
        }
        return $tds;
    }
    public function getTBodys($name) {
        $em = $this->getDoctrine()->getManager();
        
        $tbodys = array();
        switch($name){
            case 'mediciones-ISP-instituciones':
                $instituciones = $em->getRepository('QoSAdminBundle:Institucion')->findAll();
                foreach($instituciones as $institucion){
                    $usrs = '';
                    foreach($institucion->getProveedores() as $proveedor){
                        $tbodys[]['tds'] = array(
                            array(
                                'val' => '<a href="'.$this->generateUrl('Institucion_show', array('id' => $institucion->getId())).'">'.$institucion->getNombre().' ('.$institucion->getAbreviacion().')</a>',
                            ),
                            array(
                                'val' => '<a href="'.$this->generateUrl('Proveedor_show', array('id' => $proveedor->getId())).'">'.$proveedor->getNombre().' ('.$proveedor->getAbreviacion().')</a>',
                            ),
                            array(
                                'val' => $institucion->getMediciones()->count().' - '.$institucion->getPromedioTotal($proveedor),
                            ),
                        );
                    }
                }
                break;
            case 'velocidad-descarga-proveedores':
                $proveedores = $em->getRepository('QoSAdminBundle:Proveedor')->findAll();
                foreach($proveedores as $proveedor){
                    $usrs = '';
                    $tbodys[]['tds'] = array(
                        array(
                            'val' => '<a href="'.$this->generateUrl('Proveedor_show', array('id' => $proveedor->getId())).'">'.$proveedor->getNombre().' ('.$proveedor->getAbreviacion().')</a>',
                        ),
                        array(
                            'val' => $proveedor->getMedicionesInstitucion()->count(),
                        ),
                        array(
                            'val' => $proveedor->getPromedioTotal(),
                        ),
                    );
                }
                break;
        }
        return $tbodys;
    }
    
    function getNameUrl(Request $request, $name){
        if(!$name){
            $user = $this->getUser();
            if($user){
                $name = $user->getUsername();
            }
        }
        return $name;
    }
}
