<?php
namespace App\ResumeBundle\Form\Type;

use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array(
                'label' => false,
                'widget_form_group' => false,
                'attr' => array(
                    'placeholder' => 'First name'
                )
            ))
            ->add('lastName', null, array(
                'label' => false,
                'widget_form_group' => false,
                'attr' => array(
                    'placeholder' => 'Last name'
                )
            ))
            ->add('headline', null, array(
            ))
            ->add('about', 'textarea', array(
                'attr' => array(
                    'rows' => 3
                )
            ))
            ->add('contactEmail', 'email')
            ->add('contactNumber', 'tel', array(
                'format' => PhoneNumberFormat::INTERNATIONAL,
                'attr' => array(
                    'placeholder' => 'International format'
                )
            ))
            ->add('socialNetworks', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'type'   => 'student_social_network',
                'prototype' => true,
                'show_legend' => false,
                'horizontal_wrap_children' => true,
                'options' => array(
                    'label_render' => false,
                    'widget_remove_btn' => array(
                        'horizontal_wrapper_div' => array(
                            'class' => "col-sm-1"
                        ),
                        'wrapper_div' => false,
                    ),
                    'horizontal' => true,
                    'horizontal_label_offset_class' => "",
                    'horizontal_input_wrapper_class' => "col-sm-10",
                )
            ))
            ->add('country', 'country', array(
                'preferred_choices' => array('AU'),
                'label' => 'Country (current residence)'
            ))
            ->add('state', 'text', array(
                'label' => 'State (current residence)'
            ))
            ->add('city', 'text', array(
                'label' => 'City (current residence)'
            ))
            ->add('workingRight', 'choice', array(
                'choices' => array(
                    0 => 'No',
                    1 => 'Yes'
                ),
                'label' => 'Do you have full working right in Australia?'
            ))
            ->add('avatar', 'student_avatar')
            ->add('resume', 'student_resume')
            ->add('save', 'submit', array(
                'attr' => array('class' => 'save btn-info'),
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
            'data_class' =>'App\ResumeBundle\Entity\StudentProfile',
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
        return 'student_profile';
    }
}