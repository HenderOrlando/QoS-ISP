<?php

namespace QoS\MedicionesBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use QoS\AdminBundle\Entity\Institucion;

class InstitucionTransformer implements DataTransformerInterface
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
     * @param  Institucion|null $issue
     * @return string
     */
    public function transform($institucion)
    {
        if (null === $institucion) {
            return "";
        }

        return $institucion->getId();
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

        $institucion = $this->om
            ->getRepository('QoSAdminBundle:Institucion')
            ->find($id)
        ;

        if (null === $institucion) {
            throw new TransformationFailedException(sprintf(
                'La institución "%s" no existe!',
                $id
            ));
        }

        return $institucion;
    }
}