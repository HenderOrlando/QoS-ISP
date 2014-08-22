<?php
namespace QoS\AdminBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="QoS\AdminBundle\Repository\RolRepository")
 * @ORM\Table(name="rol")
 */
class Rol extends Objeto
{
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
}
