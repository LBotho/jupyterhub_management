<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\Common\Collections\ArrayCollection;
use UserBundle\Entity\User;
use UserBundle\Entity\YearGroup;
use UserBundle\Entity\SubGroup;

class DefaultController extends Controller
{
    /**
     * @Route("/users", name="users")
     */
    public function indexAction() {
        $users = $this->getDoctrine()->getRepository('UserBundle:User')->findAll();
        $yearGroups = $this->getDoctrine()->getRepository('UserBundle:YearGroup')->findAll();
        $subGroups = $this->getDoctrine()->getRepository('UserBundle:SubGroup')->findAll();

        return $this->render('UserBundle:Default:index.html.twig', array(
            'users'      => $users,
            'yearGroups' => $yearGroups,
            'subGroups'  => $subGroups,
        ));
    }

    /*****************/
    /*  USER ACTIONS */
    /*****************/

    /**
     * Add new user
     * @Route("/users/createUser", name="createUser")
     * @Method({"POST"})
     */
    public function createUserAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $scriptsPath = $this->container->getParameter('scriptsPath');
        $databaseUser = $this->getDoctrine()->getRepository('UserBundle:User')->findOneById($request->get('id'));
        $yearGroup = $em->getRepository('UserBundle:YearGroup')->findOneById($request->get('yearGroup'));

        if ($databaseUser) {
            $this->get('session')->getFlashBag()->add('danger',' L\'utilisateur avec l\'identifiant '.$request->get('id').' existe déjà.');
            return $this->redirect($this->generateUrl('users'));
        }

        $user = new User();
        $user->setId($request->get('id'))
             ->setFirstName($request->get('firstName'))
             ->setLastName($request->get('lastName'))
             ->setMail($request->get('mail'))
             ->setYearGroup($yearGroup);
        if ($request->get('subGroups')) {
            foreach ($request->get('subGroups') as $subGroupId) {
                $user->addSubGroup($em->getRepository('UserBundle:SubGroup')->findOneById($subGroupId));
            }
        }

        $em->persist($user);

        exec('sudo '.$scriptsPath.'juseradd.sh '.$user->getId(), $output, $returnedValue);
        if($returnedValue) {
            $this->get('session')->getFlashBag()->add('danger',' Erreur de création de l\'utilisateur sur le serveur.');
            return $this->redirect($this->generateUrl('users'));
        }

        exec('sudo '.$scriptsPath.'addUserToGroup.sh '.$user->getId().' '.$user->getFirstName().' '.$user->getLastName().' '.$user->getYearGroup()->getId(), $output, $returnedValue);
        if($returnedValue) {
            $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout de l\'utilisateur à la promotion sur le serveur.');
            return $this->redirect($this->generateUrl('users'));
        }

        foreach ($user->getSubGroups()->getValues() as $subGroup) {
            exec('sudo '.$scriptsPath.'addUserToGroup.sh '.$user->getId().' '.$user->getFirstName().' '.$user->getLastName().' '.$user->getYearGroup()->getId().' '.$subGroup->getId(), $output, $returnedValue);
            if($returnedValue) {
                $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout de l\'utilisateur à un groupe sur le serveur.');
                return $this->redirect($this->generateUrl('users'));
            }
        }

        try {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success',' Utilisateur ajouté avec succès.');
        } catch (\Doctrine\ORM\ORMException $e) {
            $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout de l\'utilisateur en base de données.');
        }
        return $this->redirect($this->generateUrl('users'));
     }

     /**
      * Import users from csv
      * @Route("/users/importUsers", name="importUsers")
      * @Method({"POST"})
      */
      public function importUsersAction(Request $request) {
          $em = $this->getDoctrine()->getManager();
          $scriptsPath = $this->container->getParameter('scriptsPath');
          $csvFile = $request->files->get('usersFile');
          $rows = array();

          $handle = fopen($csvFile->getRealPath(), 'r');
          $i = 0;
          while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
              $i++;
              $rows[] = $data;
          }
          fclose($handle);
          unset($rows[0]);

          foreach ($rows as $key => $row) {
              $user = new User();
              $databaseUser = $this->getDoctrine()->getRepository('UserBundle:User')->findOneById($row[0]);
              $yearGroup = $this->getDoctrine()->getRepository('UserBundle:YearGroup')->findOneById($row[4]);

              if ($databaseUser) {
                  $this->get('session')->getFlashBag()->add('danger',' L\'utilisateur avec l\'identifiant '.$row[0].' existe déjà.');
                  return $this->redirect($this->generateUrl('users'));
              }

              if ($yearGroup) {
                  $user->setId($row[0])
                       ->setFirstName($row[1])
                       ->setLastName($row[2])
                       ->setMail($row[3])
                       ->setYearGroup($this->getDoctrine()->getRepository('UserBundle:YearGroup')->findOneById($row[4]));
                  if ($row[5]) {
                      foreach ($row as $key => $field) {
                          if ($key > 4 && $field) {
                              $subGroup = $this->getDoctrine()->getRepository('UserBundle:SubGroup')->findOneById($field);
                              if ($subGroup) {
                                    $user->addSubGroup($this->getDoctrine()->getRepository('UserBundle:SubGroup')->findOneById($field));
                              } else {
                                  $this->get('session')->getFlashBag()->add('danger',' Le groupe '.$field.' n\'est pas reconnu dans le fichier .csv');
                                  return $this->redirect($this->generateUrl('users'));
                              }

                          }
                      }
                  }
              } else {
                  $this->get('session')->getFlashBag()->add('danger',' La promotion '.$row[4].' n\'est pas reconnue.');
                  return $this->redirect($this->generateUrl('users'));
              }

              $em->persist($user);

              exec('sudo '.$scriptsPath.'juseradd.sh '.$user->getId(), $output, $returnedValue);
              if($returnedValue) {
                  $this->get('session')->getFlashBag()->add('danger',' Erreur de création de l\'utilisateur sur le serveur.');
                  return $this->redirect($this->generateUrl('users'));
              }

              exec('sudo '.$scriptsPath.'addUserToGroup.sh '.$user->getId().' '.$user->getFirstName().' '.$user->getLastName().' '.$user->getYearGroup()->getId(), $output, $returnedValue);
              if($returnedValue) {
                  $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout de l\'utilisateur à la promotion sur le serveur.');
                  return $this->redirect($this->generateUrl('users'));
              }

              foreach ($user->getSubGroups()->getValues() as $subGroup) {
                  exec('sudo '.$scriptsPath.'addUserToGroup.sh '.$user->getId().' '.$user->getFirstName().' '.$user->getLastName().' '.$user->getYearGroup()->getId().' '.$subGroup->getId(), $output, $returnedValue);
                  if($returnedValue) {
                      $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout de l\'utilisateur à un groupe sur le serveur.');
                      return $this->redirect($this->generateUrl('users'));
                  }
              }
          }

          try {
              $em->flush();
              $this->get('session')->getFlashBag()->add('success',' Utilisateurs ajoutés avec succès.');
          } catch (\Doctrine\ORM\ORMException $e) {
              $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout des utilisateurs en base de données.');
          }
          return $this->redirect($this->generateUrl('users'));
      }

     /**
      * Edit user
      * @Route("/users/editUser/{id}", name="editUser")
      * @Method({"POST"})
      */
      public function editUserAction(Request $request, $id) {
          $em = $this->getDoctrine()->getManager();
          $user = $em->getRepository('UserBundle:User')->findOneById($id);
          $yearGroup = $em->getRepository('UserBundle:YearGroup')->findOneById($request->get('yearGroup'));
          $scriptsPath = $this->container->getParameter('scriptsPath');

          foreach ($user->getSubGroups()->getValues() as $subGroup) {
              $user->removeSubGroup($subGroup);
              exec('sudo '.$scriptsPath.'removeUserFromGroup.sh '.$user->getId().' '.$user->getYearGroup()->getId().' '.$subGroup->getId(), $output, $returnedValue);
              if($returnedValue) {
                  $this->get('session')->getFlashBag()->add('danger',' Erreur de suppression de l\'utilisateur d\'un groupe sur le serveur.');
                  return $this->redirect($this->generateUrl('users'));
              }
          }

          if ($user->getYearGroup() != $yearGroup) {
              exec('sudo '.$scriptsPath.'removeUserFromGroup.sh '.$user->getId().' '.$user->getYearGroup()->getId(), $output, $returnedValue);
              if($returnedValue) {
                  $this->get('session')->getFlashBag()->add('danger',' Erreur de suppression de l\'utilisateur de la promotion sur le serveur.');
                  return $this->redirect($this->generateUrl('users'));
              }

              exec('sudo '.$scriptsPath.'addUserToGroup.sh '.$user->getId().' '.$user->getFirstName().' '.$user->getLastName().' '.$user->getYearGroup()->getId(), $output, $returnedValue);
              if($returnedValue) {
                  $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout de l\'utilisateur à la promotion sur le serveur.');
                  return $this->redirect($this->generateUrl('users'));
              }
          }

          $user->setFirstName($request->get('firstName'))
               ->setLastName($request->get('lastName'))
               ->setMail($request->get('mail'))
               ->setYearGroup($yearGroup);

          foreach ($request->get('subGroups') as $subGroupId) {
              $subGroup = $em->getRepository('UserBundle:SubGroup')->findOneById($subGroupId);
              $user->addSubGroup($subGroup);
              exec('sudo '.$scriptsPath.'addUserToGroup.sh '.$user->getId().' '.$user->getFirstName().' '.$user->getLastName().' '.$user->getYearGroup()->getId().' '.$subGroup->getId(), $output, $returnedValue);
              if($returnedValue) {
                  $this->get('session')->getFlashBag()->add('danger',' Erreur d\'ajout de l\'utilisateur à un groupe sur le serveur.');
                  return $this->redirect($this->generateUrl('users'));
              }
          }

          try {
              $em->flush();
              $this->get('session')->getFlashBag()->add('success',' Utilisateur modifié avec succès');
          } catch (\Doctrine\ORM\ORMException $e) {
              $this->get('session')->getFlashBag()->add('danger',' Erreur de modification de l\'utilisateur en base de données.');
          }

          return $this->redirect($this->generateUrl('users'));
      }

      /**
       * Delete user
       * @Route("/users/deleteUser/{id}", name="deleteUser")
       */
      public function deleteUserAction($id) {
          $em = $this->getDoctrine()->getManager();
          $user = $em->getRepository('UserBundle:User')->findOneById($id);
          $scriptsPath = $this->container->getParameter('scriptsPath');

          foreach ($user->getSubGroups()->getValues() as $subGroup) {
              exec('sudo '.$scriptsPath.'removeUserFromGroup.sh '.$user->getId().' '.$user->getYearGroup()->getId().' '.$subGroup->getId(), $output, $returnedValue);
              if($returnedValue) {
                  $this->get('session')->getFlashBag()->add('danger',' Erreur de suppression de l\'utilisateur d\'un groupe sur le serveur.');
                  return $this->redirect($this->generateUrl('users'));
              }
          }

          exec('sudo '.$scriptsPath.'removeUserFromGroup.sh '.$user->getId().' '.$user->getYearGroup()->getId(), $output, $returnedValue);
          if($returnedValue) {
              $this->get('session')->getFlashBag()->add('danger',' Erreur de suppression de l\'utilisateur de la promotion sur le serveur.');
              return $this->redirect($this->generateUrl('users'));
          }

          exec('sudo '.$scriptsPath.'juserdel.sh '.$user->getId(), $output, $returnedValue);
          if($returnedValue) {
              $this->get('session')->getFlashBag()->add('danger',' Erreur de suppression de l\'utilisateur sur le serveur.');
              return $this->redirect($this->generateUrl('users'));
          }

          $em->remove($user);

          try {
              $em->flush();
              $this->get('session')->getFlashBag()->add('success',' Utilisateur supprimé avec succès.');
          } catch (\Doctrine\ORM\ORMException $e) {
              $this->get('session')->getFlashBag()->add('danger',' Erreur de suppression de l\'utilisateur en base de données.');
          }

          return $this->redirect($this->generateUrl('users'));
      }

     /**********************/
     /*  YEARGROUP ACTIONS */
     /**********************/

     /**
      * Add new year group
      * @Route("/users/createYearGroup", name="createYearGroup")
      * @Method({"POST"})
      */
     public function createYearGroupAction(Request $request) {
         $em = $this->getDoctrine()->getManager();
         $scriptsPath = $this->container->getParameter('scriptsPath');
         $databaseYearGroup = $em->getRepository('UserBundle:YearGroup')->findOneById($request->get('id'));

         if($databaseYearGroup) {
             $this->get('session')->getFlashBag()->add('danger',' La promotion avec l\'identifiant '.$request->get('id').' existe déjà.');
             $url = $this->generateUrl('users').'#yearGroupsTab';
             return $this->redirect($url);
         }

         $yearGroup = new YearGroup();
         $yearGroup->setId($request->get('id'))
                   ->setLabel($request->get('label'));
         $em->persist($yearGroup);

         exec('sudo '.$scriptsPath.'createGroups.sh '.$yearGroup->getId());
         if($returnedValue) {
             $this->get('session')->getFlashBag()->add('danger',' Erreur de création de la promotion sur le serveur.');
             $url = $this->generateUrl('users').'#yearGroupsTab';
             return $this->redirect($url);
         }

         try {
             $em->flush();
             $this->get('session')->getFlashBag()->add('success',' Promotion créée avec succès.');
         } catch (\Doctrine\ORM\ORMException $e) {
             $this->get('session')->getFlashBag()->add('danger',' Erreur de création de la promotion en base de données.');
         }

         $url = $this->generateUrl('users').'#yearGroupsTab';
         return $this->redirect($url);
     }

     /**
      * Edit year group
      * @Route("/users/editYearGroup/{id}", name="editYearGroup")
      * @Method({"POST"})
      */
     public function editYearGroupAction(Request $request, $id) {
         $em = $this->getDoctrine()->getManager();
         $yearGroup = $em->getRepository('UserBundle:YearGroup')->findOneById($id);
         $yearGroup->setLabel($request->get('label'));

         try {
             $em->flush();
             $this->get('session')->getFlashBag()->add('success',' Promotion modifiée avec succès.');
         } catch (\Doctrine\ORM\ORMException $e) {
             $this->get('session')->getFlashBag()->add('danger',' Erreur de modification de la promotion en base de données.');
         }

         $url = $this->generateUrl('users').'#yearGroupsTab';
         return $this->redirect($url);
     }

     /*********************/
     /*  SUBGROUP ACTIONS */
     /*********************/

     /**
      * Add subgroup
      * @Route("/users/createSubGroup", name="createSubGroup")
      * @Method({"POST"})
      */
     public function createSubGroupAction(Request $request) {
         $em = $this->getDoctrine()->getManager();
         $scriptsPath = $this->container->getParameter('scriptsPath');
         $databaseSubGroup = $em->getRepository('UserBundle:SubGroup')->findOneById($request->get('id'));
         $yearGroup = $em->getRepository('UserBundle:YearGroup')->findOneById($request->get('yearGroup'));

         if($databaseSubGroup) {
             $this->get('session')->getFlashBag()->add('danger',' Le groupe avec l\'identifiant '.$request->get('id').' existe déjà.');
             $url = $this->generateUrl('users').'#subGroupsTab';
             return $this->redirect($url);
         }

         $subGroup = new SubGroup();
         $subGroup->setId($request->get('id'))
                  ->setLabel($request->get('label'))
                  ->setYearGroup($yearGroup);
         $em->persist($subGroup);

         exec('sudo '.$scriptsPath.'createGroups.sh '.$yearGroup->getId().' '.$subGroup->getId(), $output, $returnedValue);
         if($returnedValue) {
             $this->get('session')->getFlashBag()->add('danger',' Erreur de création du groupe sur le serveur.');
             $url = $this->generateUrl('users').'#subGroupsTab';
             return $this->redirect($url);
         }

         try {
             $em->flush();
             $this->get('session')->getFlashBag()->add('success',' Groupe créé avec succès.');
         } catch (\Doctrine\ORM\ORMException $e) {
             $this->get('session')->getFlashBag()->add('danger',' Erreur de création du groupe en base de données.');
         }

         $url = $this->generateUrl('users').'#subGroupsTab';
         return $this->redirect($url);
     }

     /**
      * Edit subgroup
      * @Route("/users/editSubGroup/{id}", name="editSubGroup")
      * @Method({"POST"})
      */
     public function editSubGroupAction(Request $request, $id) {
         $em = $this->getDoctrine()->getManager();
         $subGroup = $em->getRepository('UserBundle:SubGroup')->findOneById($id);
         $subGroup->setLabel($request->get('label'));

         try {
             $em->flush();
             $this->get('session')->getFlashBag()->add('success',' Groupe modifié avec succès.');
         } catch (\Doctrine\ORM\ORMException $e) {
             $this->get('session')->getFlashBag()->add('danger',' Erreur de modification du groupe en base de données.');
         }

         $url = $this->generateUrl('users').'#subGroupsTab';
         return $this->redirect($url);
     }
}
