<?php
namespace QoS\AdminBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @ORM\Entity(repositoryClass="QoS\AdminBundle\Repository\RolRepository")
 * @ORM\Table(name="rol")
 */
class Rol extends Role implements RoleInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $canonical;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $fechaCreado;
    
    /**
     * @ORM\OneToMany(targetEntity="QoS\AdminBundle\Entity\Usuario", mappedBy="rol")
     */
    private $usuario;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setFechaCreado(null);
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
     * Set nombre
     *
     * @param string $nombre
     * @return Objeto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set canonical
     *
     * @param string $canonical
     * @return Objeto
     */
    public function setCanonical($canonical)
    {
        $this->canonical = $canonical;

        return $this;
    }

    /**
     * Get canonical
     *
     * @return string 
     */
    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Objeto
     */
    public function setFechaCreado($fechaCreado)
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
     * Add usuario
     *
     * @param \QoS\AdminBundle\Entity\Usuario $usuario
     * @return Rol
     */
    public function addUsuario(\QoS\AdminBundle\Entity\Usuario $usuario)
    {
        $this->usuario[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \QoS\AdminBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\QoS\AdminBundle\Entity\Usuario $usuario)
    {
        $this->usuario->removeElement($usuario);
    }

    /**
     * Get usuario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    /**
     * Get String usuario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStringUsuario()
    {
        $text = '';
        foreach($this->getUsuario() as $usr){
            $text .= $usr->__toString().'; ';
        }
        return $text;
    }
    
    public function getRole()
    {
        return 'ROLE_'.strtoupper($this->canonical);
    }
    
    public function __toString() {
        return $this->getNombre();
    }
}
