<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template("index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }



    /**
     * @Route("/styleguide", name="styleguide")
     * @Template("styleguide.html.twig")
     */
    public function styleGuideAction()
    {
        return array();
    }
}
