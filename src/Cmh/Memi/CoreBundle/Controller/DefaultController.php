<?php

namespace Cmh\Memi\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CmhMemiCoreBundle:Default:index.html.twig');
    }
}
