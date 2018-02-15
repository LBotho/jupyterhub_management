<?php

namespace NotebooksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use NotebooksBundle\Entity\Notebook;


class DefaultController extends Controller
{
    /**
     * @Route("/notebooks", name="notebooks")
     */
    public function indexAction() {
        $notebooks = $this->getDoctrine()->getRepository('NotebooksBundle:Notebook')->findAll();

        return $this->render('NotebooksBundle:Default:index.html.twig', array(
            'notebooks' => $notebooks,
        ));
    }

    /***********************/
    /*  NOTEBOOKS ACTIONS  */
    /***********************/

    /**
     * Synchronize with Google Drive notebooks
     * @Route("/notebooks/synchronizeNotebooks", name="synchronizeNotebooks")
     */
     public function synchronizeNotebooksAction() {
        $em = $this->getDoctrine()->getManager();
        $jhubuserPath = $this->getParameter('jhubuserPath');
        $idNotebooksDirectory = $this->getParameter('idNotebooksDirectory');
        $scriptsPath = $this->getParameter('scriptsPath');

        //Download Google Drive notebooks to our linux server
        exec('sudo '.$jhubuserPath.'gdrive download '.$idNotebooksDirectory.' --config '.$jhubuserPath.'.gdrive --recursive --force --path '.$jhubuserPath.'cours', $output, $returnedValue1);
        if($returnedValue1) {
            $this->get('session')->getFlashBag()->add('danger',' Erreur de téléchargement des notebooks depuis le Google Drive.');
            return $this->redirect($this->generateUrl('notebooks'));
        }

        //Fix permissions so that the admin can live edit notebooks
        exec('sudo '.$scriptsPath.'fixPermissions.sh '.$jhubuserPath.'cours', $output, $returnedValuePermissions);
        if($returnedValuePermissions) {
            $this->get('session')->getFlashBag()->add('danger',' Erreur lors de l\'application des permissions.');
            return $this->redirect($this->generateUrl('notebooks'));
        }

        //Put all notebooks directories in an array
        exec('ls '.$jhubuserPath.'cours/__notebooks__', $driveNotebooks, $returnedValue2);
        if($returnedValue2) {
            $this->get('session')->getFlashBag()->add('danger',' Erreur de lecture des notebooks présents.');
            return $this->redirect($this->generateUrl('notebooks'));
        }

        //Check deleted Notebooks then delete from database
        $notebooks = $this->getDoctrine()->getRepository('NotebooksBundle:Notebook')->findAll();
        foreach ($notebooks as $notebook) {
            if (!in_array($notebook->getLabel(),$driveNotebooks)) {
                $em->remove($notebook);
            }
        }

        //Check new Notebooks then add to database
        foreach ($driveNotebooks as $driveNotebook) {
            $notebookExists = $this->getDoctrine()->getRepository('NotebooksBundle:Notebook')->findOneByLabel($driveNotebook);
            if (!$notebookExists) {
                $newNotebook = new Notebook();
                $newNotebook->setLabel($driveNotebook);
                $em->persist($newNotebook);
            }
        }

        try {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success',' Notebooks synchronisés avec le Google Drive.');
        } catch (\Doctrine\ORM\ORMException $e) {
            $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout des notebooks en base de données.');
        }

        return $this->redirect($this->generateUrl('notebooks'));
     }
}
