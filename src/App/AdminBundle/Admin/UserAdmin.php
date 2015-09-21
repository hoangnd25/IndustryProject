<?php

namespace App\AdminBundle\Admin;

use App\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
    /** @var  UserManager $userManager */
    protected $userManager;

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        $formMapper
            ->with('Status')
            ->add('enabled', null, array('required'=>false));

        if ($subject->isStudent()) {
            $formMapper->add('visible', 'checkbox',
                array(
                    'label' => 'Student Visible',
                    'required'=>false,
                )
            );
        }
        $formMapper->end();

        $formMapper
            ->with('Account Details')
            ->add('type', 'choice', array(
                'label' => 'Account Type',
                'choices' => User::getRolesArray()
            ))
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'text', array(
                'required' => false,
                'label' => 'New Password'
            ))
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('roles', null, array('label'=>'Account Type'), 'choice', array(
                'choices' => User::getRolesArray()
            ))
            ->add('studentProfileVisibility', null,
                array(
                    'label' => 'Student Visible',
                ), 'choice',
                array(
                    'choices' => User::getStudentProfileVisibilityArray()
                )
            )
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('enabled')
            ->add('studentProfileVisibility', 'boolean', array(
                'label' => 'Student Visible',
                'template' => 'AppAdminBundle:User:list_student_visibility_type.html.twig'
            ))
            ->add('type', null, array(
                'label' => 'Account Type',
                'template' => 'AppAdminBundle:User:list_user_type.html.twig'
            ))
        ;
    }

    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    public function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
}