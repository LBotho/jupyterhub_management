<?php

namespace NbgraderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Common\Collections\ArrayCollection;
use NbgraderBundle\Entity\Assignment;
use UserBundle\Entity\YearGroup;
use UserBundle\Entity\SubGroup;

class DefaultController extends Controller
{
    /**
     * @Route("/nbgrader", name="nbgrader")
     */
    public function indexAction() {
        $assignments = $this->getDoctrine()->getRepository('NbgraderBundle:Assignment')->findAll();
        $yearGroups = $this->getDoctrine()->getRepository('UserBundle:YearGroup')->findAll();
        $subGroups = $this->getDoctrine()->getRepository('UserBundle:SubGroup')->findAll();
        $notebooks = $this->getDoctrine()->getRepository('NotebooksBundle:Notebook')->findAll();

        return $this->render('NbgraderBundle:Default:index.html.twig', array(
            'notebooks'   => $notebooks,
            'assignments' => $assignments,
            'yearGroups'  => $yearGroups,
            'subGroups'   => $subGroups,
        ));
    }

    /**
     * Add assignment
     * @Route("/nbgrader/createAssignment", name="createAssignment")
     */
    public function createAssignmentAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $scriptsPath = $this->container->getParameter('scriptsPath');

        $assignment = new Assignment();
        $assignment->setName($request->get('assignmentName'))
                   ->setLesson($request->get('lesson'))
                   ->setNotebook($em->getRepository('NotebooksBundle:Notebook')->findOneById($request->get('notebook')))
                   ->setYearGroup($em->getRepository('UserBundle:YearGroup')->findOneById($request->get('yearGroup')));

        if ($request->get('subGroups')) {
            foreach ($request->get('subGroups') as $subGroupId) {
                $subGroup = $em->getRepository('UserBundle:SubGroup')->findOneById($subGroupId);
                $assignment->addSubGroup($subGroup);
                exec('sudo '.$scriptsPath.'createAssignment.sh '.$assignment->getLesson().' '.$assignment->getName().' '.$assignment->getNotebook()->getLabel().' '.$subGroup->getYearGroup()->getId().' '.$subGroup->getId(), $output, $returnedValue);
                if($returnedValue) {
                    $this->get('session')->getFlashBag()->add('danger',' Erreur de création du devoir sur le serveur.');
                    return $this->redirect($this->generateUrl('nbgrader'));
                }
            }
        } else {
            exec('sudo '.$scriptsPath.'createAssignment.sh '.$assignment->getLesson().' '.$assignment->getName().' '.$assignment->getNotebook()->getLabel().' '.$assignment->getYearGroup()->getId(), $output, $returnedValue);
            if($returnedValue) {
                $this->get('session')->getFlashBag()->add('danger',' Erreur de création du devoir sur le serveur.');
                return $this->redirect($this->generateUrl('nbgrader'));
            }
        }

        $em->persist($assignment);

        try {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success',' Devoir créé avec succès.');
        } catch (\Doctrine\ORM\ORMException $e) {
            $this->get('session')->getFlashBag()->add('danger',' Erreur de création du devoir en base de données.');
        }

        return $this->redirect($this->generateUrl('nbgrader'));
    }

    /**
     * Get marks from assignment
     * @Route("/nbgrader/getMarks/{id}", name="getMarks")
     */
     public function getMarksAction($id) {
         $em = $this->getDoctrine()->getManager();
         $scriptsPath = $this->container->getParameter('scriptsPath');
         $jhubuserPath = $this->container->getParameter('jhubuserPath');
         $assignment = $em->getRepository('NbgraderBundle:Assignment')->findOneById($id);

         if ($assignment->getSubGroups()->getValues()) {
             foreach ($assignment->getSubGroups()->getValues() as $subGroup) {
                 exec('sudo '.$scriptsPath.'getMarks.sh '.$assignment->getName().' '.$assignment->getLesson().' '.$subGroup->getYearGroup()->getId().' '.$subGroup->getId().' 2>&1', $output, $returnedValue);
                 if($returnedValue) {
                     $this->get('session')->getFlashBag()->add('danger',' Erreur de récupération des notes. Log : '.$output[0]);
                     return $this->redirect($this->generateUrl('nbgrader'));
                 }
             }
         } else {
            exec('sudo '.$scriptsPath.'getMarks.sh '.$assignment->getName().' '.$assignment->getLesson().' '.$subGroup->getYearGroup()->getId(), $output, $returnedValue);
            if($returnedValue) {
                $this->get('session')->getFlashBag()->add('danger',' Erreur de récupération des notes. Log : '.$output[0]);
                return $this->redirect($this->generateUrl('nbgrader'));
            }
         }

         if ($assignment->getSubGroups()->getValues()) {
             foreach ($assignment->getSubGroups()->getValues() as $subGroup) {
                 $marksDirectoryPath = $jhubuserPath.'groupes/'.$assignment->getYearGroup()->getId().'/'.$subGroup->getId().'/';
                 chdir($jhubuserPath.'groupes/');
                 $marksFilename = $assignment->getName().'.csv';
                 $response = new BinaryFileResponse($marksDirectoryPath.$marksFilename);
                 $disposition = $response->headers->makeDisposition(
                     ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                     $marksFilename
                 );
                 $response->headers->set('Content-Disposition', $disposition);
                 return $response;
             }
         } else {
             $marksDirectoryPath = $jhubuserPath.'groupes/'.$assignment->getYearGroup()->getId().'/';
             chdir($jhubuserPath.'groupes/');
             $marksFilename = $assignment->getName().'.csv';
             $response = new BinaryFileResponse($marksDirectoryPath.$marksFilename);
             $disposition = $response->headers->makeDisposition(
                 ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                 $marksFilename
             );
             $response->headers->set('Content-Disposition', $disposition);
             return $response;
         }

         $this->get('session')->getFlashBag()->add('success',' Notes récupérées avec succès.');
         return $this->redirect($this->generateUrl('nbgrader'));
     }
}
