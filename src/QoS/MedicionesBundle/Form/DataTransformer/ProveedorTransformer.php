<?php

namespace QoS\MedicionesBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use QoS\AdminBundle\Entity\Proveedor;

class ProveedorTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Proveedor|null $issue
     * @return string
     */
    public function transform($proveedor)
    {
        if (null === $proveedor) {
            return "";
        }

        return $proveedor->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $proveedor = $this->om
            ->getRepository('QoSAdminBundle:Proveedor')
            ->find($id)
        ;

        if (null === $proveedor) {
            throw new TransformationFailedException(sprintf(
                'El proveedor "%s" no existe!',
                $id
            ));
        }

        return $proveedor;
    }
}