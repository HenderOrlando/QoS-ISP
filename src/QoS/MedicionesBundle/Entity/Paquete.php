<?php
namespace QoS\MedicionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="QoS\MedicionesBundle\Repository\Paqueterepository")
 * @ORM\Table(name="paquete")
 */
class Paquete
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $tam;

    /**
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    private $unidadTam;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $url;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $fullUrl;

    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionInstitucion", mappedBy="paquete")
     */
    private $configuracion;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configuracion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set tam
     *
     * @param float $tam
     * @return Paquete
     */
    public function setTam($tam)
    {
        $this->tam = $tam;

        return $this;
    }

    /**
     * Get tam
     *
     * @return float 
     */
    public function getTam()
    {
        return $this->tam;
    }

    /**
     * Set unidadTam
     *
     * @param string $unidadTam
     * @return Paquete
     */
    public function setUnidadTam($unidadTam)
    {
        $this->unidadTam = $unidadTam;

        return $this;
    }

    /**
     * Get unidadTam
     *
     * @return string 
     */
    public function getUnidadTam()
    {
        return $this->unidadTam;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Paquete
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fullUrl
     *
     * @param string $fullUrl
     * @return Paquete
     */
    public function setFullUrl($fullUrl)
    {
        $this->fullUrl = $fullUrl;

        return $this;
    }

    /**
     * Get fullUrl
     *
     * @return string 
     */
    public function getFullUrl()
    {
        return $this->fullUrl;
    }

    /**
     * Add configuracion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $configuracion
     * @return Paquete
     */
    public function addConfiguracion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $configuracion)
    {
        $this->configuracion[] = $configuracion;

        return $this;
    }

    /**
     * Remove configuracion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $configuracion
     */
    public function removeConfiguracion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $configuracion)
    {
        $this->configuracion->removeElement($configuracion);
    }

    /**
     * Get configuracion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConfiguracion()
    {
        return $this->configuracion;
    }
}
