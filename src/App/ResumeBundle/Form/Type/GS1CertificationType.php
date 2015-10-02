<?php
namespace App\ResumeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GS1CertificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'entity', array(
                'class' => 'App\ResumeBundle\Entity\GS1Certification',
                'label' => false,
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => "col-sm-4",
            ))
            ->add('file', 'file_upload', array(
                'label' => false,
                'required'      => false,
                'allow_delete' => false,
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => "col-sm-8",
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
            'data_class' =>'App\ResumeBundle\Entity\StudentGS1Certification'
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return 'student_gs1_cert';
    }
}