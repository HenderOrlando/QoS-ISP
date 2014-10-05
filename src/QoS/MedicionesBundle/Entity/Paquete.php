<?php
namespace QoS\MedicionesBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;
use QoS\AdminBundle\Entity\Objeto;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="QoS\MedicionesBundle\Repository\PaqueteRepository")
 * @ORM\Table(name="paquete")
 * @ORM\HasLifecycleCallbacks
 */
class Paquete extends Objeto
{

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $tam;

    /**
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    private $unidadTam;

    /**
     * @ORM\OneToMany(targetEntity="QoS\MedicionesBundle\Entity\MedicionInstitucion", mappedBy="paquete")
     */
    private $configuracion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    
    private $temp;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
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
//
//    /**
//     * Set tam
//     *
//     * @param float $tam
//     * @return Paquete
//     */
//    public function setTam($tam)
//    {
//        $this->tam = $tam;
//
//        return $this;
//    }

    /**
     * Get tam
     *
     * @return float 
     */
    public function getTam()
    {
        return $this->tam;
    }
//
//    /**
//     * Set unidadTam
//     *
//     * @param string $unidadTam
//     * @return Paquete
//     */
//    public function setUnidadTam($unidadTam)
//    {
//        $this->unidadTam = $unidadTam;
//
//        return $this;
//    }

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
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // haz lo que quieras para generar un nombre único
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
            
            $size = $this->getFile()->getSize();
            $unid = 'byte';
            if($size > 1024){
                $size = $size/1024;//Convertido a Kb
                $unid = 'Kb';
                if($size > 1024){
                    $size = $size/1024;//Convertido a Mb
                    $unid = 'Mb';
                    if($size > 1024){
                        $unid = 'Gb';
                        $size = $size/1024;//Convertido a Gb
                    }
                }
            }
            $this->tam = $size;
            $this->unidadTam = $unid;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);
        
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().$this->path;
    }

    public function getWebPath()
    {
        $webpath = null === $this->path
            ? null
            : $this->path;
        if(strstr($webpath,'://')){
            return $webpath;
        }
        return $this->getUploadDir().$webpath;
    }

    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../../web/'.$this->getUploadDir();       
    }

    protected function getUploadDir()
    {
        // se deshace del __DIR__ para no meter la pata
        // al mostrar el documento/imagen cargada en la vista.
        return 'uploads/paquetes/';
    }
    
    public function __toString() {
        return $this->getNombre().' ('.substr($this->getTam(), 0, 6).' '.$this->getUnidadTam().')';
    }

    public function json($returnArray = false){
        $array = array(
            'nombre' => $this->getNombre(),
            'canonical' => $this->getCanonical(),
            'webPath' => $this->getWebPath(),
        );
        if($returnArray){
            return $array;
        }
        return json_encode($array);
    }

}
