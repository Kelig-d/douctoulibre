<?php

namespace App\Controller;

use App\Repository\MedecinRepository;
use App\Repository\PatientRepository;
use App\Repository\RendezVousRepository;
use DateTime;
use DateInterval;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendrierController extends AbstractController
{
    #[Route('/calendrier', name: 'calendrier')]
    public function index(?int $medecinId,?int $patientId,PatientRepository $patientRepository,MedecinRepository $medecinRepository,RendezVousRepository $rendezVousRepository): Response
    {
        if($medecinId){
            $leUser = $medecinRepository->findOneBy(['id' => $medecinId]);
            $lesRendezVous = $rendezVousRepository->findBy(['leMedecin' => $leUser]);
        }
        else{
            $leUser = $patientRepository->findOneBy(['id' => $patientId]);
            $lesRendezVous = $rendezVousRepository->findBy(['lePatient' => $leUser]);
        }
        //création d'un tableau des rendez-vous pour le transformer en json pour le full calendar
        $rdv = array();
        foreach($lesRendezVous as $unRendezVous){
            //vérification si le rendez-vous est validé ou non par le médecin
            if($unRendezVous->getDuree()){
                $duree = $unRendezVous->getDuree();
                $background = "#00CA2A";
            }
            else{
                $duree = 60;
                $background = "#EFA111";
            }
            //création de la date de fin du rendez vous pour l'afficher correctement dans le calendrier, 1h de temps par défaut
            $dateFin = new DateTime($unRendezVous->getDateDebut()->format(('Y-m-d H:i:s')));
            $dateFin->add(new DateInterval('PT'. $duree. 'M'));
            $titre = "Rdv de ".$unRendezVous->getLePatient()->getPrenom()." ".$unRendezVous->getLePatient()->getNom();
            $rdv[] = [
                'id' => $unRendezVous->getId(),
                'start' => $unRendezVous->getDateDebut()->format('Y-m-d H:i:s'),
                'end' => $dateFin->format('Y-m-d H:i:s'),
                'title' => $titre,
                'description' => $unRendezVous->getDescription(),
                'backgroundColor' => $background
            ];
        }
        //encodage en json des rendez-vous
        $rdvJson = json_encode($rdv);
        return $this->render('calendrier/index.html.twig', [
            'controller_name' => 'CalendrierController',
            'rdv'=>$rdvJson
        ]);
    }
}
