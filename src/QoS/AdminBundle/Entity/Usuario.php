<?php
namespace QoS\AdminBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="QoS\AdminBundle\Repository\UsuarioRepository")
 * @ORM\Table(name="usuario", options={"comment":"Usuarios del sistema"})
 */
class Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=12, nullable=false)
     */
    private $docId;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
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
     * @ORM\Column(type="string", nullable=false)
     */
    private $discr;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", unique=true, length=50, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenExpire;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Rol", inversedBy="usuario")
     * @ORM\JoinColumn(name="rol", referencedColumnName="id", nullable=false)
     */
    private $rol;

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
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;

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
     * Set discr
     *
     * @param string $discr
     * @return Usuario
     */
    public function setDiscr($discr)
    {
        $this->discr = $discr;

        return $this;
    }

    /**
     * Get discr
     *
     * @return string 
     */
    public function getDiscr()
    {
        return $this->discr;
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
     * Set tokenExpire
     *
     * @param \DateTime $tokenExpire
     * @return Usuario
     */
    public function setTokenExpire($tokenExpire)
    {
        $this->tokenExpire = $tokenExpire;

        return $this;
    }

    /**
     * Get tokenExpire
     *
     * @return \DateTime 
     */
    public function getTokenExpire()
    {
        return $this->tokenExpire;
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
}
