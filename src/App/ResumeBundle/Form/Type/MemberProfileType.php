<?php
namespace App\ResumeBundle\Form\Type;

use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MemberProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', null, array(
                'render_required_asterisk' => true
            ))
            ->add('number', 'text', array(
                'label' => 'GS1 member ID number'
            ))
            ->add('contactNumber', 'tel', array(
                'format' => PhoneNumberFormat::INTERNATIONAL,
                'attr' => array(
                    'placeholder' => 'International format (e.g. +61 412 345 678)'
                ),
                'render_required_asterisk' => true
            ))
            ->add('country', 'country', array(
                'preferred_choices' => array('AU'),
                'label' => 'Country',
                'render_required_asterisk' => true
            ))
            ->add('state', 'text', array(
                'label' => 'State',
                'render_required_asterisk' => true
            ))
        ;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>'App\ResumeBundle\Entity\MemberProfile',
            'render_fieldset' => false,
            'show_legend' => false
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return 'member_profile';
    }
}