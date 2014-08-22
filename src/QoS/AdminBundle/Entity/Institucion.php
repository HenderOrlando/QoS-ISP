<?php
namespace QoS\AdminBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * Institucion
 *
 * @ORM\Entity(repositoryClass="QoS\AdminBundle\Repository\InstitucionRepository")
 * @ORM\Table(name="institucion", options={"comment":"Institución donde se realiza la medición"})
 */
class Institucion extends Objeto
{
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $abreviacion;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionInstitucion", mappedBy="colegio")
     */
    private $medicion;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medicion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set abreviacion
     *
     * @param string $abreviacion
     * @return Institucion
     */
    public function setAbreviacion($abreviacion)
    {
        $this->abreviacion = $abreviacion;

        return $this;
    }

    /**
     * Get abreviacion
     *
     * @return string 
     */
    public function getAbreviacion()
    {
        return $this->abreviacion;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Institucion
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
     * @return Institucion
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
     * Add medicion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $medicion
     * @return Institucion
     */
    public function addMedicion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicion)
    {
        $this->medicion[] = $medicion;

        return $this;
    }

    /**
     * Remove medicion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $medicion
     */
    public function removeMedicion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicion)
    {
        $this->medicion->removeElement($medicion);
    }

    /**
     * Get medicion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedicion()
    {
        return $this->medicion;
    }
}
