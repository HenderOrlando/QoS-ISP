<?php
namespace QoS\MedicionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="QoS\AdminBundle\Repository\MedicionProveedorRepository")
 * @ORM\Table(name="medicionProveedor")
 */
class MedicionProveedor
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $actual;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\MedicionesBundle\Entity\Medicion", inversedBy="medicionProveedor")
     * @ORM\JoinColumn(name="medicion", referencedColumnName="id", nullable=false)
     */
    private $medicion;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Proveedor", inversedBy="medicionProveedor")
     * @ORM\JoinColumn(name="proveedor", referencedColumnName="id", nullable=false)
     */
    private $proveedor;

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
     * Set actual
     *
     * @param boolean $actual
     * @return MedicionProveedor
     */
    public function setActual($actual)
    {
        $this->actual = $actual;

        return $this;
    }

    /**
     * Get actual
     *
     * @return boolean 
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * Set medicion
     *
     * @param \QoS\MedicionesBundle\Entity\Medicion $medicion
     * @return MedicionProveedor
     */
    public function setMedicion(\QoS\MedicionesBundle\Entity\Medicion $medicion)
    {
        $this->medicion = $medicion;

        return $this;
    }

    /**
     * Get medicion
     *
     * @return \QoS\MedicionesBundle\Entity\Medicion 
     */
    public function getMedicion()
    {
        return $this->medicion;
    }

    /**
     * Set proveedor
     *
     * @param \QoS\AdminBundle\Entity\Proveedor $proveedor
     * @return MedicionProveedor
     */
    public function setProveedor(\QoS\AdminBundle\Entity\Proveedor $proveedor)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \QoS\AdminBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }
}
