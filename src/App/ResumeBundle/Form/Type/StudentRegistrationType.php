<?php
namespace App\ResumeBundle\Form\Type;

use App\ResumeBundle\Entity\StudentEducation;
use App\ResumeBundle\Entity\StudentProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class StudentRegistrationType extends AbstractType
{
    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct($entityManager = null)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institution', 'entity', array(
                'class' => 'App\ResumeBundle\Entity\Institution',
                'required' => true,
                'mapped' => false,
                'empty_value' => 'Select institution',
                'constraints' => array(
                    new NotBlank(array('message'=>'Please select an institution'))
                )
            ))
        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event)  {
                $institution = $event->getForm()->get('institution')->getData();
                $studentProfile = $event->getData();
                if($institution !== null && $studentProfile instanceof StudentProfile){
                    $education = new StudentEducation();
                    $education->setStudentProfile($studentProfile);
                    $education->setDegree("---");
                    $education->setInstitution($institution);
                    /** @var StudentProfile $studentProfile */
                    $studentProfile->getEducations()->add($education);
                }
            }
        );
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
        return 'student_profile_registration';
    }
}