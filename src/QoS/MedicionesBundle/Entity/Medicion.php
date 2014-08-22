<?php
namespace QoS\MedicionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="QoS\AdminBundle\Repository\MedicionRepository")
 * @ORM\Table(name="medicion")
 */
class Medicion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=false, options={"unsigned":true})
     */
    private $upload;

    /**
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    private $unidadUpload;

    /**
     * @ORM\Column(type="float", nullable=false, options={"unsigned":true})
     */
    private $download;

    /**
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    private $unidadDownload;

    /**
     * @ORM\Column(type="float", nullable=false, options={"unsigned":true})
     */
    private $promedio;

    /**
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    private $unidadPromedio;

    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionProveedor", mappedBy="medicion")
     */
    private $medicionProveedor;

    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionInstitucion", mappedBy="medicion")
     */
    private $medicionInstitucion;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medicionProveedor = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medicionInstitucion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set upload
     *
     * @param float $upload
     * @return Medicion
     */
    public function setUpload($upload)
    {
        $this->upload = $upload;

        return $this;
    }

    /**
     * Get upload
     *
     * @return float 
     */
    public function getUpload()
    {
        return $this->upload;
    }

    /**
     * Set unidadUpload
     *
     * @param string $unidadUpload
     * @return Medicion
     */
    public function setUnidadUpload($unidadUpload)
    {
        $this->unidadUpload = $unidadUpload;

        return $this;
    }

    /**
     * Get unidadUpload
     *
     * @return string 
     */
    public function getUnidadUpload()
    {
        return $this->unidadUpload;
    }

    /**
     * Set download
     *
     * @param float $download
     * @return Medicion
     */
    public function setDownload($download)
    {
        $this->download = $download;

        return $this;
    }

    /**
     * Get download
     *
     * @return float 
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * Set unidadDownload
     *
     * @param string $unidadDownload
     * @return Medicion
     */
    public function setUnidadDownload($unidadDownload)
    {
        $this->unidadDownload = $unidadDownload;

        return $this;
    }

    /**
     * Get unidadDownload
     *
     * @return string 
     */
    public function getUnidadDownload()
    {
        return $this->unidadDownload;
    }

    /**
     * Set promedio
     *
     * @param float $promedio
     * @return Medicion
     */
    public function setPromedio($promedio)
    {
        $this->promedio = $promedio;

        return $this;
    }

    /**
     * Get promedio
     *
     * @return float 
     */
    public function getPromedio()
    {
        return $this->promedio;
    }

    /**
     * Set unidadPromedio
     *
     * @param string $unidadPromedio
     * @return Medicion
     */
    public function setUnidadPromedio($unidadPromedio)
    {
        $this->unidadPromedio = $unidadPromedio;

        return $this;
    }

    /**
     * Get unidadPromedio
     *
     * @return string 
     */
    public function getUnidadPromedio()
    {
        return $this->unidadPromedio;
    }

    /**
     * Add medicionProveedor
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionProveedor $medicionProveedor
     * @return Medicion
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

    /**
     * Add medicionInstitucion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion
     * @return Medicion
     */
    public function addMedicionInstitucion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion)
    {
        $this->medicionInstitucion[] = $medicionInstitucion;

        return $this;
    }

    /**
     * Remove medicionInstitucion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion
     */
    public function removeMedicionInstitucion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion)
    {
        $this->medicionInstitucion->removeElement($medicionInstitucion);
    }

    /**
     * Get medicionInstitucion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedicionInstitucion()
    {
        return $this->medicionInstitucion;
    }
}
