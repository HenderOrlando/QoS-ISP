<?php
namespace QoS\MedicionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="QoS\MedicionesBundle\Repository\MedicionInstitucionRepository")
 * @ORM\Table(name="medicionInstitucion")
 */
class MedicionInstitucion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $numPaquetes;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\MedicionesBundle\Entity\Paquete", inversedBy="configuracion")
     * @ORM\JoinColumn(name="paquete", referencedColumnName="id", nullable=false)
     */
    private $paquete;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Institucion", inversedBy="medicion")
     * @ORM\JoinColumn(name="colegio", referencedColumnName="id")
     */
    private $colegio;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\MedicionesBundle\Entity\Medicion", inversedBy="medicionInstitucion")
     * @ORM\JoinColumn(name="medicion", referencedColumnName="id")
     */
    private $medicion;

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
     * Set numPaquetes
     *
     * @param integer $numPaquetes
     * @return MedicionInstitucion
     */
    public function setNumPaquetes($numPaquetes)
    {
        $this->numPaquetes = $numPaquetes;

        return $this;
    }

    /**
     * Get numPaquetes
     *
     * @return integer 
     */
    public function getNumPaquetes()
    {
        return $this->numPaquetes;
    }

    /**
     * Set paquete
     *
     * @param \QoS\MedicionesBundle\Entity\Paquete $paquete
     * @return MedicionInstitucion
     */
    public function setPaquete(\QoS\MedicionesBundle\Entity\Paquete $paquete)
    {
        $this->paquete = $paquete;

        return $this;
    }

    /**
     * Get paquete
     *
     * @return \QoS\MedicionesBundle\Entity\Paquete 
     */
    public function getPaquete()
    {
        return $this->paquete;
    }

    /**
     * Set colegio
     *
     * @param \QoS\AdminBundle\Entity\Institucion $colegio
     * @return MedicionInstitucion
     */
    public function setColegio(\QoS\AdminBundle\Entity\Institucion $colegio = null)
    {
        $this->colegio = $colegio;

        return $this;
    }

    /**
     * Get colegio
     *
     * @return \QoS\AdminBundle\Entity\Institucion 
     */
    public function getColegio()
    {
        return $this->colegio;
    }

    /**
     * Set medicion
     *
     * @param \QoS\MedicionesBundle\Entity\Medicion $medicion
     * @return MedicionInstitucion
     */
    public function setMedicion(\QoS\MedicionesBundle\Entity\Medicion $medicion = null)
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
}
