<?php

namespace QoS\MedicionesBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use QoS\AdminBundle\Entity\Usuario;

class UsuarioTransformer implements DataTransformerInterface
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
     * @param  Usuario|null $issue
     * @return string
     */
    public function transform($usuario)
    {
        if (null === $usuario) {
            return "";
        }

        return $usuario->getId();
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

        $usuario = $this->om
            ->getRepository('QoSAdminBundle:Usuario')
            ->find($id)
        ;

        if (null === $usuario) {
            throw new TransformationFailedException(sprintf(
                'El usuario "%s" no existe!',
                $id
            ));
        }

        return $usuario;
    }
}