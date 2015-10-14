<?php

namespace App\ContentBundle\Twig\Extension;

use App\ContentBundle\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class MenuTwigExtension extends \Twig_Extension
{
    /** @var EntityManager $em */
    protected $em;

    protected $cache;

    /**
     * MenuTwigExtension constructor.
     */
    public function __construct(Registry $doctrine, CacheProvider $cache)
    {
        $this->em = $doctrine->getManager();
        $this->cache = $cache;
    }


    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('cms_menu_get', array($this, 'menuFunction')),
        );
    }


    public function menuFunction($id)
    {
        $cacheId = Menu::CACHE_PREFIX.$id;
        $menu = $this->cache->fetch($cacheId);
        if(!$menu){
            $query = $this->em->createQueryBuilder()
                ->select('m')
                ->from('AppContentBundle:Menu', 'm')
                ->where('m.id = :id')->setParameter('id', $id)
                ->getQuery();
            $menu = $query->getSingleResult(Query::HYDRATE_ARRAY);

            $query = $this->em->createQueryBuilder()
                ->select('i')
                ->from('AppContentBundle:MenuItem', 'i')
                ->where('i.menu = :id')->setParameter('id', $id)
                ->getQuery();
            $menuItems = $query->getArrayResult();
            $menu['items'] = $menuItems;

            $this->cache->save($cacheId, $menu);
        }
        return $menu;
    }

    public function getName()
    {
        return 'menu_extension';
    }
}