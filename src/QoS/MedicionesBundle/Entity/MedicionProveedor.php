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
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Proveedor", inversedBy="medicionesProveedor")
     * @ORM\JoinColumn(name="proveedor", referencedColumnName="id", nullable=false)
     */
    private $proveedor;

    /**
     * @ORM\ManyToOne(targetEntity="QoS\AdminBundle\Entity\Usuario", inversedBy="medicionesProveedor")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
     */
    private $usuario;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $fechaCreado;

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
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }
    
    /**
     * Set velocidad
     *
     * @param float $velocidad
     * @return MedicionProveedor
     */
    public function setVelocidad($velocidad)
    {
        $this->velocidad = $velocidad;

        return $this;
    }

    /**
     * Get velocidad
     *
     * @return float 
     */
    public function getVelocidad()
    {
        return $this->velocidad;
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
     * @return MedicionProveedor
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
     * @return MedicionProveedor
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
     * @return MedicionProveedor
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
     * @return MedicionProveedor
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
     * @return MedicionProveedor
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
     * @return MedicionProveedor
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
     * @return MedicionProveedor
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
     * @return MedicionProveedor
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
     * @return MedicionProveedor
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
    public function getSpeedUpload()
    {
        return $this->speedUpload;
    }

    /**
     * Set speedDownload
     *
     * @param float $speedDownload
     * @return MedicionProveedor
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
    public function getSpeedDownload()
    {
        return $this->speedDownload;
    }

    /**
     * Set lengthUpload
     *
     * @param float $lengthUpload
     * @return MedicionProveedor
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
    public function getLengthUpload()
    {
        return $this->lengthUpload;
    }

    /**
     * Set lengthDownload
     *
     * @param float $lengthDownload
     * @return MedicionProveedor
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
    public function getLengthDownload()
    {
        return $this->lengthDownload;
    }
}
