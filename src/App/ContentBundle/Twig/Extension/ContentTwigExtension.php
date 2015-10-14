<?php
/**
 * Created by PhpStorm.
 * User: hoangnd
 * Date: 14/10/15
 * Time: 12:33 PM
 */

namespace App\ContentBundle\Twig\Extension;

use Doctrine\Bundle\DoctrineBundle\Registry;

class ContentTwigExtension extends \Twig_Extension
{
    protected $em;

    /**
     * ContentTwigExtension constructor.
     */
    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('cms_content_get', array($this, 'contentFunction')),
        );
    }


    public function contentFunction($id)
    {
        $content = $this->em->getRepository('AppContentBundle:Content')->find($id);
        return $content
            ;
    }

    public function getName()
    {
        return 'content_extension';
    }
}