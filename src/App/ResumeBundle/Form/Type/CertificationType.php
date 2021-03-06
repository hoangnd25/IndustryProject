<?php
namespace App\ResumeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

class CertificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => false,
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => "col-sm-5",
                'attr' => array(
                    'placeholder' => 'Certification name',
                    'maxlength' => false
                ),
                'constraints' => array(
                    new Length(array('max'=>100)),
                    new NotBlank()
                ),
                'error_bubbling' => false
            ))
            ->add('file', 'file_upload', array(
                'label' => false,
                'required'      => false,
                'allow_delete' => false,
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => "col-sm-7",
                'constraints' => array(
                    new File(array(
                        'maxSize' => '1M',
                        'mimeTypes' => array(
                            'application/pdf',
                            'application/x-pdf',
                            'image/*'
                        ),
                        'mimeTypesMessage' => 'Invalid file, please upload PDF or image'
                    ))
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
            'data_class' =>'App\ResumeBundle\Entity\StudentCertification'
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return 'student_cert';
    }
}