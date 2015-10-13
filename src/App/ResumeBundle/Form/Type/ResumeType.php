<?php
namespace App\ResumeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ResumeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', 'file_upload', array(
            'label' => false,
            'required'      => false,
            'allow_delete' => false,
            'constraints' => array(
                new File(array(
                    'maxSize' => '1M',
                    'mimeTypes' => array(
                        'application/pdf',
                        'application/x-pdf',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/msword'
                    ),
                    'mimeTypesMessage' => 'Invalid file, please upload PDF or MS Word'
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
            'data_class' =>'App\ResumeBundle\Entity\StudentResume'
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return 'student_resume';
    }
}