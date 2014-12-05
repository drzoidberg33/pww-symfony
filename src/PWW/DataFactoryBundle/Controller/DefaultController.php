<?php

namespace PWW\DataFactoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PWWDataFactoryBundle:Default:index.html.twig');
    }
}
