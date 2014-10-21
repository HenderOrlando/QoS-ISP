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
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\MedicionesBundle\Entity\Paquete", inversedBy="configuracion")
     * @ORM\JoinColumn(name="paquete", referencedColumnName="id", nullable=true)
     */
    private $paquete;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Institucion", inversedBy="mediciones")
     * @ORM\JoinColumn(name="colegio", referencedColumnName="id", nullable=false)
     */
    private $institucion;
    
    /**
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Proveedor", inversedBy="medicionesInstitucion")
     * @ORM\JoinColumn(name="proveedor", referencedColumnName="id", nullable=false)
     */
    private $proveedor;
    
    /**
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Usuario", inversedBy="medicionesInstitucion")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $timeTotal;//seg

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $timeNameLookup;//seg

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $timeConnect;//seg

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $timePreTransfer;//seg

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $timeStartTransfer;//seg

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $timeRedirect;//seg

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $sizeUpload;//bytes

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $sizeDownload;//bytes

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $speedUpload;//bytes

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $speedDownload;

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $lengthUpload;//bytes

    /**
     * @ORM\Column(type="float", nullable=true, options={"unsigned":true})
     */
    private $lengthDownload;//bytes
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $fechaCreado;
    
    private $numPaquetes;

    public function __construct() {
        $this->fechaCreado = new \DateTime();
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
     * Set numPaquetes
     *
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
     * @param \QoS\AdminBundle\Entity\Institucion $institucion
     * @return MedicionInstitucion
     */
    public function setInstitucion(\QoS\AdminBundle\Entity\Institucion $institucion = null)
    {
        $this->institucion = $institucion;

        return $this;
    }

    /**
     * Get colegio
     *
     * @return \QoS\AdminBundle\Entity\Institucion 
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * Set proveedor
     *
     * @param \QoS\AdminBundle\Entity\Proveedor $proveedor
     * @return ProveedorInstitucion
     */
    public function setProveedor(\QoS\AdminBundle\Entity\Proveedor $proveedor = null)
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

    /**
     * Set usuario
     *
     * @param \QoS\AdminBundle\Entity\Usuario $usuario
     * @return UsuarioInstitucion
     */
    public function setUsuario(\QoS\AdminBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \QoS\AdminBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Objeto
     */
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getFechaCreado($formato = null)
    {
        $fecha = $this->fechaCreado;
        if(is_string($formato)){
            $fecha = $fecha->format($formato);
        }
        return $fecha;
    }
    
    /**
     * Set velocidad
     *
     * @param float $velocidad
     * @return MedicionInstitucion
     */
    public function setVelocidad($velocidad)
    {
        $this->velocidad = $velocidad;
        $size = $velocidad;
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
        $this->setUnidad($unid.'/seg');

        return $this;
    }
    
    public function getNombre($short = false){
        if($short){
            return 'Medición en la Institución '.$this->getInstitucion();
        }
        return 'Medición del Proveedor '.$this->getProveedor().' en la Institución '.$this->getInstitucion();
    }

    /**
     * Set timeTotal
     *
     * @param float $timeTotal
     * @return MedicionInstitucion
     */
    public function setTimeTotal($timeTotal)
    {
        $this->timeTotal = $timeTotal;

        return $this;
    }

    /**
     * Get timeTotal
     *
     * @return float 
     */
    public function getTimeTotal()
    {
        return $this->timeTotal;
    }

    /**
     * Set timeNameLookup
     *
     * @param float $timeNameLookup
     * @return MedicionInstitucion
     */
    public function setTimeNameLookup($timeNameLookup)
    {
        $this->timeNameLookup = $timeNameLookup;

        return $this;
    }

    /**
     * Get timeNameLookup
     *
     * @return float 
     */
    public function getTimeNameLookup()
    {
        return $this->timeNameLookup;
    }

    /**
     * Set timeConnect
     *
     * @param float $timeConnect
     * @return MedicionInstitucion
     */
    public function setTimeConnect($timeConnect)
    {
        $this->timeConnect = $timeConnect;

        return $this;
    }

    /**
     * Get timeConnect
     *
     * @return float 
     */
    public function getTimeConnect()
    {
        return $this->timeConnect;
    }

    /**
     * Set timePreTransfer
     *
     * @param float $timePreTransfer
     * @return MedicionInstitucion
     */
    public function setTimePreTransfer($timePreTransfer)
    {
        $this->timePreTransfer = $timePreTransfer;

        return $this;
    }

    /**
     * Get timePreTransfer
     *
     * @return float 
     */
    public function getTimePreTransfer()
    {
        return $this->timePreTransfer;
    }

    /**
     * Set timeStartTransfer
     *
     * @param float $timeStartTransfer
     * @return MedicionInstitucion
     */
    public function setTimeStartTransfer($timeStartTransfer)
    {
        $this->timeStartTransfer = $timeStartTransfer;

        return $this;
    }

    /**
     * Get timeStartTransfer
     *
     * @return float 
     */
    public function getTimeStartTransfer()
    {
        return $this->timeStartTransfer;
    }

    /**
     * Set timeRedirect
     *
     * @param float $timeRedirect
     * @return MedicionInstitucion
     */
    public function setTimeRedirect($timeRedirect)
    {
        $this->timeRedirect = $timeRedirect;

        return $this;
    }

    /**
     * Get timeRedirect
     *
     * @return float 
     */
    public function getTimeRedirect()
    {
        return $this->timeRedirect;
    }

    /**
     * Set sizeUpload
     *
     * @param float $sizeUpload
     * @return MedicionInstitucion
     */
    public function setSizeUpload($sizeUpload)
    {
        $this->sizeUpload = $sizeUpload;

        return $this;
    }

    /**
     * Get sizeUpload
     *
     * @return float 
     */
    public function getSizeUpload()
    {
        return $this->sizeUpload;
    }

    /**
     * Set sizeDownload
     *
     * @param float $sizeDownload
     * @return MedicionInstitucion
     */
    public function setSizeDownload($sizeDownload)
    {
        $this->sizeDownload = $sizeDownload;

        return $this;
    }

    /**
     * Get sizeDownload
     *
     * @return float 
     */
    public function getSizeDownload()
    {
        return $this->sizeDownload;
    }

    /**
     * Set speedUpload
     *
     * @param float $speedUpload
     * @return MedicionInstitucion
     */
    public function setSpeedUpload($speedUpload)
    {
        $this->speedUpload = $speedUpload;

        return $this;
    }

    /**
     * Get speedUpload
     *
     * @return float 
     */
    public function getSpeedUpload($humanize = false)
    {
        $var = $this->speedUpload;
        if($humanize){
            $var = $this->humanize($var, true);
        }
        return $var;
    }

    /**
     * Set speedDownload
     *
     * @param float $speedDownload
     * @return MedicionInstitucion
     */
    public function setSpeedDownload($speedDownload)
    {
        $this->speedDownload = $speedDownload;

        return $this;
    }

    /**
     * Get speedDownload
     *
     * @return float 
     */
    public function getSpeedDownload($humanize = false)
    {
        $var = $this->speedDownload;
        if($humanize){
            $var = $this->humanize($var, true);
        }
        return $var;
    }

    /**
     * Set lengthUpload
     *
     * @param float $lengthUpload
     * @return MedicionInstitucion
     */
    public function setLengthUpload($lengthUpload)
    {
        $this->lengthUpload = $lengthUpload;

        return $this;
    }

    /**
     * Get lengthUpload
     *
     * @return float 
     */
    public function getLengthUpload($humanize = false)
    {
        $var = $this->lengthUpload;
        if($humanize){
            $var = $this->humanize($var, true);
        }
        return $var;
    }

    /**
     * Set lengthDownload
     *
     * @param float $lengthDownload
     * @return MedicionInstitucion
     */
    public function setLengthDownload($lengthDownload)
    {
        $this->lengthDownload = $lengthDownload;

        return $this;
    }

    /**
     * Get lengthDownload
     *
     * @return float 
     */
    public function getLengthDownload($humanize = false)
    {
        $var = $this->lengthDownload;
        if($humanize){
            $var = $this->humanize($var, true);
        }
        return $var;
    }
    
    public function humanize($size, $units_ = true){
        $units = array('bytes', 'KB', 'MB', 'GB', 'TB', 'PB');
        $ord = floor(log($size) / log(1024));
        $ord = min(max(0, $ord), count($units) - 1);
        $s = round(($size / pow(1024, $ord)) * 100) / 100;
        if($units_){
            return $s.' '.$units[$ord];
        }
        return $s;
    }
    
    public function json($returnArray = false){
        $array = array(
            'institucion' => $this->getInstitucion()->json(true),
            'proveedor' => $this->getProveedor()->json(true),
            'paquete' => $this->getPaquete()->json(true),
            'usuario' => $this->getUsuario()->json(true),
            'lengthDownload' => $this->getLengthDownload(),
            'lengthUpload' => $this->getLengthUpload(),
            'nombre' => $this->getNombre(true),
            'nombreShort' => $this->getNombre(),
            'timeTotal' => $this->getTimeTotal(),
            'sizeUpload' => $this->getSizeUpload(),
            'speedUpload' => $this->getSpeedUpload(),
            'timeConnect' => $this->getTimeConnect(),
            'sizeDownload' => $this->getSizeDownload(),
            'timeRedirect' => $this->getTimeRedirect(),
            'speedDownload' => $this->getSpeedDownload(),
            'timeNamelookup' => $this->getTimeNameLookup(),
            'timePretransfer' => $this->getTimePreTransfer(),
            'timeStarttransfer' => $this->getTimeStartTransfer(),
        );
        if($returnArray){
            return $array;
        }
        return json_encode($array);
    }
}
