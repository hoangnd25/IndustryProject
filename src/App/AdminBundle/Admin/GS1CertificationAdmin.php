<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;

class GS1CertificationAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'attr' => array(
                    'maxlength' => false
                )
            ))
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('name')
            ->assertLength(array('max' => 100))
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
        ;
    }
}