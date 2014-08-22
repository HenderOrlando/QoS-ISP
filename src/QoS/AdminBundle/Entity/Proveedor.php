<?php
namespace QoS\AdminBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="QoS\AdminBundle\Repository\ProveedorRepository")
 * @ORM\Table(name="proveedor", options={"comment":"Provedor del Servicio de Internet - IPS"})
 */
class Proveedor extends Objeto
{
    /**
     * @ORM\Column(type="float", nullable=false, options={"unsigned":true})
     */
    private $holgura;

    /**
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    private $unidadHolgura;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionProveedor", mappedBy="proveedor")
     */
    private $medicionProveedor;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medicionProveedor = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set holgura
     *
     * @param float $holgura
     * @return Proveedor
     */
    public function setHolgura($holgura)
    {
        $this->holgura = $holgura;

        return $this;
    }

    /**
     * Get holgura
     *
     * @return float 
     */
    public function getHolgura()
    {
        return $this->holgura;
    }

    /**
     * Set unidadHolgura
     *
     * @param string $unidadHolgura
     * @return Proveedor
     */
    public function setUnidadHolgura($unidadHolgura)
    {
        $this->unidadHolgura = $unidadHolgura;

        return $this;
    }

    /**
     * Get unidadHolgura
     *
     * @return string 
     */
    public function getUnidadHolgura()
    {
        return $this->unidadHolgura;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Proveedor
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Proveedor
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Add medicionProveedor
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionProveedor $medicionProveedor
     * @return Proveedor
     */
    public function addMedicionProveedor(\QoS\MedicionesBundle\Entity\MedicionProveedor $medicionProveedor)
    {
        $this->medicionProveedor[] = $medicionProveedor;

        return $this;
    }

    /**
     * Remove medicionProveedor
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionProveedor $medicionProveedor
     */
    public function removeMedicionProveedor(\QoS\MedicionesBundle\Entity\MedicionProveedor $medicionProveedor)
    {
        $this->medicionProveedor->removeElement($medicionProveedor);
    }

    /**
     * Get medicionProveedor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedicionProveedor()
    {
        return $this->medicionProveedor;
    }
}
