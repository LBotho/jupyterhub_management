<?php
namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use UserBundle\Entity\YearGroup;
use UserBundle\Entity\SubGroup;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $CSI1 = new YearGroup();
        $CSI1->setId('CSI1')
             ->setLabel('Cycle Sciences de l\'Ingénieur 1');

        $CIR1 = new YearGroup();
        $CIR1->setId('CIR1')
             ->setLabel('Cycle Informatique et Réseaux 1');

        $CSI2 = new YearGroup();
        $CSI2->setId('CSI2')
             ->setLabel('Cycle Sciences de l\'Ingénieur 2');

        $CIR2 = new YearGroup();
        $CIR2->setId('CIR2')
             ->setLabel('Cycle Informatique et Réseaux 2');

        $CSI3 = new YearGroup();
        $CSI3->setId('CSI3')
             ->setLabel('Cycle Sciences de l\'Ingénieur 3');

        $CIR3 = new YearGroup();
        $CIR3->setId('CIR3')
             ->setLabel('Cycle Informatique et Réseaux 3');

        $M1 = new YearGroup();
        $M1->setId('M1')
           ->setLabel('Master 1');

        $M2 = new YearGroup();
        $M2->setId('M2')
           ->setLabel('Master 2');

        $manager->persist($CSI1);
        $manager->persist($CIR1);
        $manager->persist($CSI2);
        $manager->persist($CIR2);
        $manager->persist($CSI3);
        $manager->persist($CIR3);
        $manager->persist($M1);
        $manager->persist($M2);

        $M1ROB = new SubGroup();
        $M1ROB->setId('M1_ROB')
              ->setLabel('M1 Robotique')
              ->setYearGroup($M1);

        $M1GL = new SubGroup();
        $M1GL->setId('M1_GL')
             ->setLabel('M1 Génie Logiciel')
             ->setYearGroup($M1);

        $M1NRJ = new SubGroup();
        $M1NRJ->setId('M1_NRJ')
              ->setLabel('M1 Energies renouvelables')
              ->setYearGroup($M1);

        $M1IA = new SubGroup();
        $M1IA->setId('M1_IA')
             ->setLabel('M1 Ingénieur d\'Affaire')
             ->setYearGroup($M1);

        $M1SE = new SubGroup();
        $M1SE->setId('M1_SE')
             ->setLabel('M1 Systèmes embarqués')
             ->setYearGroup($M1);

        $M1TBM = new SubGroup();
        $M1TBM->setId('M1_TBM')
              ->setLabel('M1 Technologies du Bio-médical')
              ->setYearGroup($M1);

        $M1TR = new SubGroup();
        $M1TR->setId('M1_TR')
             ->setLabel('M1 Télécoms et Réseaux')
             ->setYearGroup($M1);

        $manager->persist($M1ROB);
        $manager->persist($M1GL);
        $manager->persist($M1NRJ);
        $manager->persist($M1IA);
        $manager->persist($M1SE);
        $manager->persist($M1TBM);
        $manager->persist($M1TR);

        $user1 = new User();
        $user1->setId('lbotho18')
              ->setFirstName('Loïc')
              ->setLastName('Bothorel')
              ->setMail('loic.bothorel@isen-bretagne.fr')
              ->setYearGroup($M1)
              ->addSubGroup($M1ROB);

        $user2 = new User();
        $user2->setId('pmicha18')
              ->setFirstName('Paul')
              ->setLastName('Michaud')
              ->setMail('paul.michaud@isen-bretagne.fr')
              ->setYearGroup($M1)
              ->addSubGroup($M1ROB);

        $manager->persist($user1);
        $manager->persist($user2);

        $manager->flush();
    }
}
