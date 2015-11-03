<?php
namespace App\ResumeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class SocialNetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'choice', array(
            'choices' => array(
                'Facebook' => 'Facebook',
                'LinkedIn' => 'LinkedIn',
                'Twitter' => 'Twitter'
            ),
            'label' => false,
            'required' => true,
            'widget_form_group' => false,
            'horizontal_input_wrapper_class' => "col-sm-4",
        ))
        ->add('url', 'text', array(
            'label' => false,
            'widget_form_group' => false,
            'horizontal_input_wrapper_class' => "col-sm-8",
            'constraints' => array(
                new Url(),
                new NotBlank()
            ),
            'attr' => array(
                'placeholder' => 'Full url (e.g http://linkedIn.com)'
            ),
            'error_bubbling' => false
        ));
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>'App\ResumeBundle\Entity\StudentSocialNetwork'
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return 'student_social_network';
    }
}