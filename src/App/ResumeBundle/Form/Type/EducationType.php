<?php
namespace App\ResumeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EducationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institution', 'entity', array(
                'class' => 'App\ResumeBundle\Entity\Institution',
                'label' => false,
                'required' => true,
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => "col-sm-4",
            ))
            ->add('degree', null , array(
                'label' => false,
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => "col-sm-8",
                'attr' => array(
                    'placeholder' => 'Degree (e.g. Bachelor of Commerce) '
                )
            ));
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
            'data_class' =>'App\ResumeBundle\Entity\StudentEducation'
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return 'student_education';
    }
}