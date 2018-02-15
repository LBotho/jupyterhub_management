<?php

namespace DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction()
    {
        $notebooks = $this->getDoctrine()->getRepository('NotebooksBundle:Notebook')->findAll();
        $users = $this->getDoctrine()->getRepository('UserBundle:User')->findAll();
        $assignments = $this->getDoctrine()->getRepository('NbgraderBundle:Assignment')->findAll();
        $timezone = date_default_timezone_get();

        return $this->render('DashboardBundle:Default:index.html.twig', array(
            'notebooks'   => $notebooks,
            'users'       => $users,
            'assignments' => $assignments,
            'timezone'    => $timezone,
        ));
    }
}
