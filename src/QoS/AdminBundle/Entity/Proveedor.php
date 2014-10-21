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
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $abreviacion;
    
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
     * @ORM\OrderBy({"fechaCreado" = "DESC"})
     */
    private $medicionesProveedor;
    
    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionInstitucion", mappedBy="proveedor")
     * @ORM\OrderBy({"fechaCreado" = "DESC"})
     */
    private $medicionesInstitucion;
    
    /**
     * @ORM\ManyToMany(targetEntity="QoS\AdminBundle\Entity\Institucion", mappedBy="proveedores")
     */
    private $instituciones;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->medicionesProveedor = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medicionesIntitucion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->instituciones = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function addMedicionesProveedor(\QoS\MedicionesBundle\Entity\MedicionProveedor $medicionProveedor)
    {
        $this->medicionesProveedor[] = $medicionProveedor;

        return $this;
    }

    /**
     * Remove medicionProveedor
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionProveedor $medicionProveedor
     */
    public function removeMedicionProveedor(\QoS\MedicionesBundle\Entity\MedicionProveedor $medicionProveedor)
    {
        $this->medicionesProveedor->removeElement($medicionProveedor);
    }

    /**
     * Get medicionProveedor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedicionesProveedor()
    {
        return $this->medicionesProveedor;
    }

    /**
     * Add medicionInstitucion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion
     * @return Institucion
     */
    public function addMedicionseInstitucion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion)
    {
        $this->medicionesInstitucion[] = $medicionInstitucion;

        return $this;
    }

    /**
     * Remove medicionInstitucion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion
     */
    public function removeMedicionesInstitucion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion)
    {
        $this->medicionesInstitucion->removeElement($medicionInstitucion);
    }

    /**
     * Get medicionInstitucion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedicionesInstitucion()
    {
        return $this->medicionesInstitucion;
    }
    
    public function getMediciones($tipo){
        switch ($tipo) {
            case 'institucion':
                return $this->getMedicionesInstitucion();
            case 'proveedor':
                return $this->getMedicionesProveedor();
            default:
                return array_merge($this->getMedicionesProveedor()->toArray(), $this->getMedicionesInstitucion());
        }
    }
    
    /**
     * Add institucion
     *
     * @param \QoS\AdminBundle\Entity\Institucion $institucion
     * @return Institucion
     */
    public function addInstitucion(\QoS\AdminBundle\Entity\Institucion $institucion)
    {
        $this->instituciones[] = $institucion;

        return $this;
    }

    /**
     * Remove institucion
     *
     * @param \QoS\AdminBundle\Entity\Institucion $institucion
     */
    public function removeInstitucion(\QoS\AdminBundle\Entity\Institucion $institucion)
    {
        $this->instituciones->removeElement($institucion);
    }

    /**
     * Get institucion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInstituciones()
    {
        return $this->instituciones;
    }
    
    public function getPromedioDownload(Institucion $institucion = null, $humanize = true){
        return $this->getPromedio($institucion, true, $humanize);
    }
    public function getPromedioUpload(Institucion $institucion = null, $humanize = true){
        return $this->getPromedio($institucion,false, $humanize);
    }
    public function getPromedioTotal(Institucion $institucion = null, $humanize = true){
        return $this->getPromedio($institucion, null, $humanize);
    }
    private function getPromedio(Institucion $institucion = null, $download = false, $humanize = true){
        $promedio = 0;
        $count = 0;
        $medicion = null;
        foreach ($this->getMedicionesInstitucion() as $medicion) {
            $speed = 0;
            if(is_null($institucion) || $medicion->getInstitucion()->getId() === $institucion->getId()){
                if(!is_null($download) && $download){
                    $speed = $medicion->getSpeedDownload();
                }elseif(!is_null($download) && !$download){
                    $speed = $medicion->getSpeedUpload();
                }else{
                    $speed = ($medicion->getSpeedDownload() + $medicion->getSpeedUpload())/2;
                }
                $promedio += $speed;
                $count++;
            }
        }
        if($count === 0){
            $promedio = 0;
            $count = 1;
        }
        if($humanize){
            if(is_object($medicion) && method_exists($medicion,'humanize')){
                return $medicion->humanize($promedio/$count,false);//byte/seg
            }
            return $medicion->humanize($promedio/$count).'/seg';
        }
        return 0;
    }
    
    public function __toString() {
        return $this->getNombre();
    }

    public function json($returnArray = false){
        $array = array(
            'nombre' => $this->getNombre(),
            'canonical' => $this->getCanonical(),
            'abreviacion' => $this->getAbreviacion(),
            'direccion' => $this->getDireccion(),
            'telefono' => $this->getTelefono(),
            'holgura' => $this->getHolgura(),
            'unidadHolgura' => $this->getUnidadHolgura(),
        );
        if($returnArray){
            return $array;
        }
        return json_encode($array);
    }

}
