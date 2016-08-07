<?php

namespace Lfarm\DevCollabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BackController extends Controller
{
    /**
     * @Route("/dashboard")
     * @Template()
     */
    public function devDashboardAction()
    {
        return array();
    }
}
