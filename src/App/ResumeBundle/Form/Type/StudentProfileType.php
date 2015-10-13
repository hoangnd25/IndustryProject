<?php
namespace App\ResumeBundle\Form\Type;

use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class StudentProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array(
                'label' => false,
                'widget_form_group' => false,
                'attr' => array(
                    'placeholder' => 'First name',
                ),
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100))
                )
            ))
            ->add('lastName', null, array(
                'label' => false,
                'widget_form_group' => false,
                'attr' => array(
                    'placeholder' => 'Last name'
                ),
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max' => 100))
                )
            ))
            ->add('headline', null, array(
                'label' => 'Occupation'
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
                ),
                'invalid_message' => 'Phone number must follow international format (e.g. +61 412 345 678)'
            ))
            ->add('industryPreference', 'entity', array(
                'class' => 'App\ResumeBundle\Entity\Industry',
                'multiple' => true
            ))
            ->add('employmentStatus', 'entity', array(
                'class' => 'App\ResumeBundle\Entity\EmploymentStatus',
                'empty_data' => '',
                'empty_value' => 'N/A',
                'required' => false
            ))
            ->add('gs1Certifications', 'collection', array(
                'label' => 'GS1 certifications',
                'allow_add' => true,
                'allow_delete' => true,
                'type'   => 'student_gs1_cert',
                'prototype' => true,
                'horizontal_wrap_children' => true,
                'error_bubbling' => false,
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
                    'horizontal_input_wrapper_class' => "col-sm-11",
                )
            ))
            ->add('certifications', 'collection', array(
                'label' => 'Certifications',
                'allow_add' => true,
                'allow_delete' => true,
                'type'   => 'student_cert',
                'prototype' => true,
                'horizontal_wrap_children' => true,
                'error_bubbling' => false,
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
                    'horizontal_input_wrapper_class' => "col-sm-11",
                )
            ))
            ->add('educations', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'type'   => 'student_education',
                'prototype' => true,
                'horizontal_wrap_children' => true,
                'error_bubbling' => false,
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
                    'horizontal_input_wrapper_class' => "col-sm-11",
                )
            ))
            ->add('socialNetworks', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'type'   => 'student_social_network',
                'prototype' => true,
                'horizontal_wrap_children' => true,
                'error_bubbling' => false,
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
                    'horizontal_input_wrapper_class' => "col-sm-11",
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
                'attr' => array('class' => 'save btn-sm btn-info'),
            ));

        // Make sure resume is uploaded
        $builder->get('resume')->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
            $resume = $event->getData();
            if($resume->getId() === null && $resume->getFile() === null)
                $event->getForm()->get('file')->addError(new FormError("You must upload a resume"));
        });
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
            'show_legend' => false,
            'attr' => array(
                'novalidate' => 'novalidate'
            )
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