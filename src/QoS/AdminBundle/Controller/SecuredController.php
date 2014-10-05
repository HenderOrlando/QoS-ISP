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
        $rta = array_merge($array, array(
            'name' => $name,
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
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
