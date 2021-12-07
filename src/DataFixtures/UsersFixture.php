<?php

namespace App\DataFixtures;

use App\Entity\Horaires;
use App\Entity\Jour;
use App\Entity\Medecin;
use App\Entity\Patient;
use App\Entity\Specialite;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UsersFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $timestamp = strtotime('next Monday');
        $lesJours = array();
        for ($i = 0; $i < 7; $i++) {
            $jour = new Jour();
            $jour->setJour(strftime('%A', $timestamp));
            $manager->persist($jour);
            $manager->flush();
            $lesJours[] = $jour;
            $timestamp = strtotime('+1 day', $timestamp);
        }
        $lesSpecialites = array();
        $lesSpecialitesNom = ["Addictologie",
        "Algologie",
        "Allergologie",
        "Anesthésie-Réanimation",
        "Cancérologie",
        "Cardio-vasculaire HTA",
        "Chirurgie",
        "Dermatologie",
        "Diabétologie-Endocrinologie",
        "Génétique",
        "Gériatrie",
        "Gynécologie-Obstétrique",
        "Hématologie",
        "Hépato-gastro-entérologie",
        "Imagerie médicale",
        "Immunologie",
        "Infectiologie",
        "Médecine du sport",
        "Médecine du travail",
        "Médecine générale",
        "Médecine légale",
        "Médecine physique et de réadaptation",
        "Néphrologie",
        "Neurologie",
        "Nutrition",
        "Ophtalmologie",
        "ORL",
        "Pédiatrie",
        "Pneumologie",
        "Psychiatrie",
        "Rhumatologie",
        "Sexologie",
        "Toxicologie",
        "Urologie"];
        foreach($lesSpecialitesNom as $specialite){
            $newSecialite = new Specialite();
            $newSecialite->setLibelle($specialite);
            array_push($lesSpecialites,$newSecialite);
            $manager->persist($newSecialite);
            $manager->flush();
        }
        $random_names = ["Henri","Marc","Gill","Robert","Nicolas","Denis","Sebastien","Louis"];
        for($i=0;$i<20;$i++){
            $medecin = new Medecin();
            $medecin->setNom($random_names[rand(0,count($random_names)-1)]);
            $medecin->setPrenom($random_names[rand(0,count($random_names)-1)]);
            $medecin->setRoles(['ROLE_MEDECIN']);
            $medecin->setEmail("m".$i."@gmail.com");
            $medecin->setPassword("$2y$13$0EJieTsfXbercqjmLAbyhO8jnCh3sfl.K4hxlU/OLrlrDA8KxipDe");
            $medecin->setAdresse($i." rue ".$random_names[rand(0,count($random_names)-1)]);
            $medecin->setCarteVitale(true);
            $medecin->setCp(rand(10000,99999));
            $medecin->setDescription("Medecin");
            $medecin->setSexe(rand(0,1));
            $medecin->setVille("Marseille");
            $medecin->setLaSpecialite($lesSpecialites[rand(0,count($lesSpecialites)-1)]);
            $telephone = "";
            for($j=0;$j<10;$j++){
                $telephone.=(string)rand(0,9);
            }

            $medecin->setTelephone($telephone);
            $manager->persist($medecin);
            for($j=0;$j<7;$j++){
                $horaire = new Horaires();
                $horaire->setLeJour($lesJours[$j]);
                $horaire->setHeureMatinDebut(new \DateTime('8:00:00'));
                $horaire->setHeureMatinFin(new \DateTime("12:00:00"));
                $horaire->setHeureApremDebut(new \DateTime("13:30:00"));
                $horaire->setHeureApremFin(new \DateTime("17:30:00"));
                $horaire->setLeMedecin($medecin);
                $manager->persist($horaire);
                $manager->flush();
            }
            $manager->flush();
        }
        for($i=0;$i<20;$i++){
            $patient = new Patient();
            $patient->setAdresse($i." rue ".$random_names[rand(0,count($random_names)-1)]);
            $patient->setCp(rand(10000,99999));
            $patient->setDateNaissance(new \DateTime(date('Y-m-d')));
            $patient->setEmail("p".$i."@gmail.com");
            $patient->setRoles(['ROLE_PATIENT']);
            $patient->setNom($random_names[rand(0,count($random_names)-1)]);
            $patient->setPrenom($random_names[rand(0,count($random_names)-1)]);
            $patient->setNomNaissance($random_names[rand(0,count($random_names)-1)]);
            $patient->setPassword("$2y$13$0EJieTsfXbercqjmLAbyhO8jnCh3sfl.K4hxlU/OLrlrDA8KxipDe");
            $patient->setSexe(rand(0,1));
            $patient->setVille("Marseille");
            $telephone = "";
            for($j=0;$j<10;$j++){
                $telephone.=(string)rand(0,9);
            }
            $patient->setTelephone($telephone);
            $manager->persist($patient);
            $manager->flush();
        }
    }
}
