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
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionInstitucion", mappedBy="institucion")
     */
    private $mediciones;

    /**
     * @ORM\ManyToMany(targetEntity="QoS\AdminBundle\Entity\Proveedor", inversedBy="instituciones")
     * @ORM\JoinTable(name="proveedor_institucion",
     *  joinColumns={@ORM\JoinColumn(name="institucion_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")}
     * )
     */
    private $proveedores;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mediciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion
     * @return Institucion
     */
    public function addMedicion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion)
    {
        $this->mediciones[] = $medicionInstitucion;

        return $this;
    }

    /**
     * Remove medicion
     *
     * @param \QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion
     */
    public function removeMedicion(\QoS\MedicionesBundle\Entity\MedicionInstitucion $medicionInstitucion)
    {
        $this->mediciones->removeElement($medicionInstitucion);
    }

    /**
     * Get medicion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMediciones()
    {
        return $this->mediciones;
    }
    
    public function getPromedioDownload(Proveedor $proveedor = null, $humanize = true){
        return $this->getPromedio($proveedor, true, $humanize);
    }
    public function getPromedioUpload(Proveedor $proveedor = null, $humanize = true){
        return $this->getPromedio($proveedor,false, $humanize);
    }
    public function getPromedioTotal(Proveedor $proveedor = null, $humanize = true){
        return $this->getPromedio($proveedor, null, $humanize);
    }
    private function getPromedio(Proveedor $proveedor = null, $download = false, $humanize = true){
        $promedio = 0;
        $count = 0;
        $medicion = null;
        foreach ($this->getMediciones() as $medicion) {
            $speed = 0;
            if(is_null($proveedor) || $medicion->getProveedor()->getId() === $proveedor->getId()){
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
            return $medicion->humanize($promedio/$count).'/seg';
        }
        if(is_object($medicion) && method_exist($medicion,'humanize')){
            return $medicion->humanize($promedio/$count,false);//byte/seg
        }
        return 0;
    }

    /**
     * Add proveedor
     *
     * @param \QoS\AdminBundle\Entity\Proveedor $proveedor
     * @return Institucion
     */
    public function addProveedor(\QoS\AdminBundle\Entity\Proveedor $proveedor)
    {
        $this->proveedores[] = $proveedor;

        return $this;
    }

    /**
     * Remove proveedor
     *
     * @param \QoS\AdminBundle\Entity\Proveedor $proveedor
     */
    public function removeProveedor(\QoS\AdminBundle\Entity\Proveedor $proveedor)
    {
        $this->proveedores->removeElement($proveedor);
    }

    /**
     * Get proveedor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProveedores()
    {
        return $this->proveedores;
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
        );
        if($returnArray){
            return $array;
        }
        return json_encode($array);
    }

}
