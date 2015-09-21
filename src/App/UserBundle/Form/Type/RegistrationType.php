<?php
/**
 * Created by PhpStorm.
 * User: hoangnd
 * Date: 27/08/15
 * Time: 9:29 PM
 */

namespace App\UserBundle\Form\Type;

use App\ResumeBundle\Form\Transformer\StudentAccessCodeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    protected $entityManager;

    /**
     * RegistrationType constructor.
     * @param $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activatedAccessCode', 'text', array(
            'required' => false
        ));

        $builder->get('activatedAccessCode')
            ->addModelTransformer(new StudentAccessCodeTransformer($this->entityManager));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}