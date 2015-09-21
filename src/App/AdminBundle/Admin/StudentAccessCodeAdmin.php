<?php

namespace App\AdminBundle\Admin;

use App\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class StudentAccessCodeAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('activated', null, array(
                'required' => false
            ))
            ->add('code')
            ->add('user', null, array(
                'disabled' => true
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('activated')
            ->add('user.username', null, array('label'=> 'Student username'))
            ->add('user.email', null, array('label'=> 'Student email'))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('code')
            ->add('user', null, array('label'=> 'Student'))
            ->add('activated')
        ;
    }
}