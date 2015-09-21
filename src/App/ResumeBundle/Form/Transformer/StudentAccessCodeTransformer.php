<?php

namespace App\ResumeBundle\Form\Transformer;

use App\ResumeBundle\Entity\StudentAccessCode;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StudentAccessCodeTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (access code) to a string.
     *
     * @param  StudentAccessCode|null $code
     * @return string
     */
    public function transform($code)
    {
        if (null === $code) {
            return '';
        }

        return $code->getCode();
    }

    /**
     * Transforms a string to an object (access code).
     *
     * @param  string $codeString
     * @return StudentAccessCode|null
     * @throws TransformationFailedException if object (access code) is not found.
     */
    public function reverseTransform($codeString)
    {
        // no access code? It's optional, so that's ok
        if (!$codeString) {
            return;
        }

        $code = $this->entityManager
            ->getRepository('AppResumeBundle:StudentAccessCode')
            ->find($codeString)
        ;

        if (null === $code) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'Access code "%s" is invalid!',
                $codeString
            ));
        }

        return $code;
    }
}