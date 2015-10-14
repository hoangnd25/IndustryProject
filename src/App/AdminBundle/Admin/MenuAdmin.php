<?php

namespace App\AdminBundle\Admin;

use App\ContentBundle\Entity\Menu;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class MenuAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id', null, array(
                'disabled' => true,
                'help_block' => 'This is machine readable name'
            ))
            ->add('name')
            ->add('items', 'sonata_type_collection', array(
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
            ))
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
            ->addIdentifier('id')
            ->addIdentifier('name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('create');
        $collection->remove('delete');
    }

    protected function clearCache($id){
        $cache = $this->getConfigurationPool()->getContainer()->get('cache');
        $cache->delete(Menu::CACHE_PREFIX.$id);
    }

    public function prePersist($object)
    {
        foreach ($object->getItems() as $item) {
            $item->setMenu($object);
        }
        $this->clearCache($object->getId());
    }

    public function preUpdate($object)
    {
        foreach ($object->getItems() as $item) {
            $item->setMenu($object);
        }
        $this->clearCache($object->getId());
    }
}