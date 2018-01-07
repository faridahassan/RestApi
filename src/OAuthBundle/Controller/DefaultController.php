<?php

namespace OAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OAuthBundle:Default:index.html.twig', array('name' => $name));
    }
}
