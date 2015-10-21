<?php
namespace App\ResumeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', null, array(
                'horizontal_label_class' => '',
                'horizontal_input_wrapper_class' => '',
                'required' => false
            ))
            ->add('industry', 'entity', array(
                'label' => 'Industry preference',
                'class' => 'App\ResumeBundle\Entity\Industry',
                'multiple' => true,
                'horizontal_label_class' => '',
                'horizontal_input_wrapper_class' => '',
                'required' => false
            ))
            ->add('gs1Certification', 'entity', array(
                'label' => 'GS1 certification',
                'class' => 'App\ResumeBundle\Entity\GS1Certification',
                'multiple' => true,
                'empty_value' => 'All',
                'empty_data' => null,
                'horizontal_label_class' => '',
                'horizontal_input_wrapper_class' => '',
                'required' => false
            ))
            ->add('employmentStatus', 'entity', array(
                'label' => 'Employment status',
                'class' => 'App\ResumeBundle\Entity\EmploymentStatus',
                'multiple' => true,
                'empty_value' => 'All',
                'empty_data' => null,
                'horizontal_label_class' => '',
                'horizontal_input_wrapper_class' => '',
                'required' => false
            ))
            ->add('availableFrom', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'label' => 'Available for employment from',
                'attr' => array(
                    'class' => 'date-picker'
                ),
                'horizontal_label_class' => '',
                'horizontal_input_wrapper_class' => '',
                'required' => false
            ))
            ->add('country', 'country', array(
                'label' => 'Country',
                'multiple' => true,
                'horizontal_label_class' => '',
                'horizontal_input_wrapper_class' => '',
                'required' => false
            ))
            ->add('institution', 'entity', array(
                'label' => 'Institution',
                'class' => 'App\ResumeBundle\Entity\Institution',
                'multiple' => true,
                'horizontal_label_class' => '',
                'horizontal_input_wrapper_class' => '',
                'required' => false
            ))
            ->add('workingRight', 'choice', array(
                'choices' => array(
                    1 => 'Yes',
                    0 => 'No'
                ),
                'empty_value' => 'Any',
                'horizontal_label_class' => '',
                'horizontal_input_wrapper_class' => '',
                'required' => false
            ))
        ;

        $builder->setMethod('GET');
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>'App\ResumeBundle\Model\StudentFilter',
            'csrf_protection' => false
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return 'filter';
    }
}