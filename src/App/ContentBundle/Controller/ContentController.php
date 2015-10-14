<?php

namespace App\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContentController extends Controller
{
    /**
     * @Route("/content/{id}/print", name="content_print")
     * @Template()
     */
    public function printAction($id)
    {
        $content = $this->getDoctrine()->getManager()->getRepository("AppContentBundle:Content")->find($id);
        if(!$content)
            throw new NotFoundHttpException("Not found");

        return array('content' => $content);
    }
}
