<?php
namespace QoS\AdminBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="QoS\AdminBundle\Repository\UsuarioRepository")
 * @ORM\Table(name="usuario", options={"comment":"Usuarios del sistema"})
 */
class Usuario implements AdvancedUserInterface, \Serializable, EquatableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=12, nullable=false)
     */
    private $docId;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $usernamecanonical;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $clave;

    /**
     * @ORM\Column(type="guid", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $salt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $fechaCreado;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", unique=true, length=50, nullable=true)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $token = null;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Rol", inversedBy="usuario")
     * @ORM\JoinColumn(name="rol", referencedColumnName="id", nullable=false)
     */
    private $rol;
    
    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionInstitucion", mappedBy="usuario")
     */
    private $medicionesInstitucion;
    
    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionProveedor", mappedBy="usuario")
     */
    private $medicionesProveedor;

    public function __construct() {
        $this->setFechaCreado(null);
        if(is_null($this->token)){
            $this->setToken(md5(time()));
        }
        $this->medicionesInstitucion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medicionesProveedor = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add medicionInstitucion
     *
     * @param \QoS\AdminBundle\Entity\MedicionInstitucion $medicion
     * @return Rol
     */
    public function addMedicionInstitucion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicion)
    {
        $this->medicion[] = $medicion;

        return $this;
    }

    /**
     * Remove medicionInstitucion
     *
     * @param \QoS\AdminBundle\Entity\MedicionInstitucion $medicion
     */
    public function removeMedicionInstitucion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicion)
    {
        $this->medicionesInstitucion->removeElement($medicion);
    }

    /**
     * Get medicionesInstitucion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedicionesInstitucion()
    {
        return $this->medicionesInstitucion;
    }
    
    /**
     * Add medicionProveedor
     *
     * @param \QoS\AdminBundle\Entity\MedicionProveedor $medicion
     * @return Rol
     */
    public function addMedicionProveedor(\QoS\MedicionesBundle\Entity\MedicionProveedor $medicion)
    {
        $this->medicion[] = $medicion;

        return $this;
    }

    /**
     * Remove medicionProveedor
     *
     * @param \QoS\AdminBundle\Entity\MedicionProveedor $medicion
     */
    public function removeMedicionProveedor(\QoS\MedicionesBundle\Entity\MedicionProveedor $medicion)
    {
        $this->medicionesProveedor->removeElement($medicion);
    }

    /**
     * Get medicionesProveedor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedicionesProveedor()
    {
        return $this->medicionesProveedor;
    }

    /**
     * Get medicionesProveedor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMediciones($tipo)
    {
        switch($tipo){
            case 'institucion':
                return $this->getMedicionesInstitucion();
            case 'proveedor':
                return $this->getMedicionesProveedor();
        }
    }
    
    /**
     * Get id
     *
     * @return guid 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set docId
     *
     * @param string $docId
     * @return Usuario
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;

        return $this;
    }

    /**
     * Get docId
     *
     * @return string 
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Set clave
     *
     * @param string $clave
     * @return Usuario
     */
    public function setClave($clave)
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * Get clave
     *
     * @return string 
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;
        $this->usernamecanonical = $this->normaliza($username);
        return $this;
    }
    
    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
//
//    /**
//     * Set usernamecanonical
//     *
//     * @param string $usernamecanonical
//     * @return Usuario
//     */
//    public function setUsernamecanonical($usernamecanonical)
//    {
//        $this->usernamecanonical = $usernamecanonical;
//
//        return $this;
//    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsernamecanonical()
    {
        return $this->usernamecanonical;
    }

    /**
     * Set salt
     *
     * @param guid $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return guid 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Usuario
     */
    public function setFechaCreado($fechaCreado = null)
    {
        $this->fechaCreado = new \DateTime();
//        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Usuario
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set rol
     *
     * @param \QoS\AdminBundle\Entity\Rol $rol
     * @return Usuario
     */
    public function setRol(\QoS\AdminBundle\Entity\Rol $rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \QoS\AdminBundle\Entity\Rol 
     */
    public function getRol()
    {
        return $this->rol;
    }

    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return $this->getClave();
    }

    public function setPassword($clave) {
        return $this->setClave($clave);
    }

    public function getRoles() {
        return array(
            $this->getRol()->getRole(),
        );
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->clave,
            $this->username,
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->clave,
            $this->username,
        ) = unserialize($serialized);
    }

    public function isEqualTo(UserInterface $user)
    {
//        die($user->getPassword());
        if (!$user instanceof Usuario) {
            return false;
        }

        if ($this->clave !== $user->getPassword()) {
            return false;
        }
//
        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }
        
        if ($this->usernamecanonical !== $user->getUsernamecanonical()) {
            return false;
        }

        return true;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return true;
    }
    
    public function __toString() {
        return $this->getEmail();
    }

    function normaliza ($cadena){
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = str_replace(' ','-',strtolower($cadena));
        return utf8_encode($cadena);
    }

    public function json($returnArray = false){
        $array = array(
            'docid' => $this->getDocId(),
            'email' => $this->getEmail(),
            'username' => $this->getUsername(),
            'usernameCanonical' => $this->getUsernamecanonical(),
        );
        if($returnArray){
            return $array;
        }
        return json_encode($array);
    }

}
