<?php
namespace App\ResumeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EducationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institution', 'entity', array(
                'class' => 'App\ResumeBundle\Entity\Institution',
                'label' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'institution-select'
                ),
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => "col-sm-6 col-xs-12 institution-wrapper"
            ))
            ->add('otherInstitution', 'text', array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Institution name'
                ),
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => 'other-institution-wrapper col-sm-6 col-xs-12 hidden',
                'constraints' => array(
                    new Length(array('max'=>100))
                )
            ))
            ->add('degree', null , array(
                'label' => false,
                'widget_form_group' => false,
                'horizontal_input_wrapper_class' => "col-sm-6 col-xs-12 pull-right",
                'constraints' => array(
                    new Length(array('max'=>100)),
                    new NotBlank()
                ),
                'attr' => array(
                    'placeholder' => 'Degree (e.g. Bachelor of Commerce) ',
                    'maxlength' => false
                )
            ))
        ;

        // If institution is empty remove that field
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $ev){
            $edu = $ev->getData();
            if($edu === null)
                return;
            if($edu->getInstitution() === null){
                $ev->getForm()->remove('institution');
            }
        });

        // Remove institution if other institution has an error or has a value
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
            $edu = $event->getData();
            if((!$edu->getInstitution())&&(!$edu->getOtherInstitution())){
                $event->getForm()->remove('institution');
                $event->getForm()->get('otherInstitution')->addError(new FormError("Institution name is required"));
            }
            if((!$edu->getInstitution())&&($edu->getOtherInstitution())){
                $event->getForm()->remove('institution');
            }
        });
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $newChoice = new ChoiceView(array(), '***', 'Other institution');
        if(array_key_exists('institution',$view->children))
            $view->children['institution']->vars['choices'][] = $newChoice;
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