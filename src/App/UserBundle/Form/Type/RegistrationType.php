<?php

namespace App\UserBundle\Form\Type;

use App\ResumeBundle\Form\Transformer\StudentAccessCodeTransformer;
use App\ResumeBundle\Form\Type\MemberProfileType;
use App\ResumeBundle\Form\Type\StudentRegistrationType;
use App\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Valid;

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
        $builder
            ->add('type', 'choice', array(
                'label' => 'I am a',
                'choices' => array(
                    User::ROLE_STUDENT => 'Student',
                    User::ROLE_GS1_MEMBER => 'Member',
                ),
                'data' => 'student',
                'attr' => array(
                    'class' => 'account_type'
                )
            ))
            ->add('firstName', null, array(
                'attr' => array(
                    'maxlength' => false
                )
            ))
            ->add('lastName', null, array(
                'attr' => array(
                    'maxlength' => false
                )
            ))
            ->add('toc', 'checkbox', array(
                'mapped' => false,
                'label' => 'I agree to <a id="toc" href="#">terms & conditions</a>',
                'constraints' => array(
                    new IsTrue(array('message' => 'You must agree to terms & conditions'))
                )
            ))
            ->add('studentProfile', new StudentRegistrationType($this->entityManager), array(
                'label' => false,
                'widget_form_group' => false,
                'widget_type' => 'inline'
            ))
        ;

        $builder->remove('username');

        $formModifier = function (FormInterface $form, $type = null) {
            if($type === User::ROLE_GS1_MEMBER){
                $form
                    ->add('memberProfile', new MemberProfileType(), array(
                        'label' => false,
                        'widget_form_group' => false,
                        'widget_type' => 'inline',
                        'constraints' => array(new Valid())
                    ))
                    ->remove("studentProfile")
                ;
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier){
                $data = $event->getData();
                $formModifier($event->getForm(), $data !== null ? $data->getType() : null);
            }
        );

        $builder->get('type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $type = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $type);
            }
        );

//        $builder->get('activatedAccessCode')
//            ->addModelTransformer(new StudentAccessCodeTransformer($this->entityManager));
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