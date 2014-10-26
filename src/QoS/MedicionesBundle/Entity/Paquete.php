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
     * @ORM\Column(type="string", length=255, nullable=false)
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
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @return string 
     */
    public function setPath($path)
    {
        
//        if(strstr('http://', $path) !== FALSE || strstr('https://', $path) !== false){// (FTP|DNS|DHCP|HTTP|HTTPS|POP|SMTP|SSH|TELNET|TFTP|LDAP|XMPP)
            $this->path = $path;
//        }
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
            
            $size = 0;
            if($this->getFile()){
                $size = $this->getFile()->getSize();
            }else{
                $type       = "\x08";
                $code       = "\x00";
                $checksum   = "\x7d\x4b";
                $identifier = "\x00\x00";
                $seqNumber  = "\x00\x00";
                $data       = "PingQoSISP";

                $package  = $type.$code.$checksum.$identifier.$seqNumber.$data;
                $size = mb_strlen($package, '8bit');
            }
            if($size === false){
                $size = 1;
            }
            $units = array('bytes', 'KB', 'MB', 'GB', 'TB', 'PB');
            $ord = floor(log($size) / log(1024));
            $ord = min(max(0, $ord), count($units) - 1);
            $s = round(($size / pow(1024, $ord)) * 100) / 100;
            
            $this->tam = $s;
            $this->unidadTam = $units[$ord];
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
    
    function ping($host = null, $timeout = 1) {
        if(is_null($host)){
            $host = $this->getPath();
        }
        /* ICMP ping de paquete con un checksum pre-calculado */
        // Crea el paquete
        $type       = "\x08";
        $code       = "\x00";
        $checksum   = "\x7d\x4b";
        $identifier = "\x00\x00";
        $seqNumber  = "\x00\x00";
        $data       = "PingQoSISP";

        $package  = $type.$code.$checksum.$identifier.$seqNumber.$data;
//        $checksum = $this->icmpChecksum($package);//Calcula el checksum
//        $package = $type.$code.$checksum.$identifier.$seqNumber.$data;
        
        $socket = socket_create(AF_INET, SOCK_RAW, 1);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
        socket_connect($socket, $host, null);

        $ts = microtime(true);
        socket_send($socket, $package, strLen($package), 0);
        if (socket_read($socket, 255)){
            $result = round(microtime(true) - $ts, 4);
        }
        else{
            $result = false;
        }
        socket_close($socket);

        return $result;
    }
    function icmpChecksum($data){
       if (strlen($data)%2)
           $data .= "\x00";

       $bit = unpack('n*', $data);
       $sum = array_sum($bit);

       while ($sum >> 16)
           $sum = ($sum >> 16) + ($sum & 0xffff);

       return pack('n*', ~$sum);
    }

}
