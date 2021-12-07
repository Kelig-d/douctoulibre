<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\RendezVous;
use App\Repository\JourRepository;
use App\Form\PrendreRendezVousType;
use App\Repository\MedecinRepository;
use App\Repository\HorairesRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedecinPageController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
        public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/medecin/page/{medecin}', name: 'medecin_page')]
    public function index(int $medecin, MedecinRepository $medecinRepository, RendezVousRepository $rendezVousRepository,HorairesRepository $horairesRepository,JourRepository $jourRepository, Request $request, EntityManagerInterface $manager): Response
    {
        //création d'une variable erreur qui va afficher le problème à l'utilisateur s'il y en a un
        $error ="";
        //Récupération du médecin et de ses rendez vous
        $leMedecin = $medecinRepository->findOneBy(['id' => $medecin]);
        $lesRendezVous = $rendezVousRepository->findBy(['leMedecin' => $leMedecin]);
        //formulaire pour ajouter un rendez-vous
        $rendezVous = new RendezVous();
        $form = $this->createForm(PrendreRendezVousType::class, $rendezVous);
        $form->handleRequest($request);
        //test si un rendez-vous existe déjà au même moment avec ce médecin
        foreach($lesRendezVous as $leRendezVous){
            if($leRendezVous->getDateDebut() == $rendezVous->getDateDebut()){
                $error = "Un rendez-vous à déjà été pris à ce créneau";
                break;
            }
        }
        //test si le rendez-vous voulu rentre dans les horaires du médecin
            $leJour = $jourRepository->findOneBy(['jour' => $rendezVous->getDateDebut()->format('l')]);
            $lesHorairesDuMedecin = $horairesRepository->findOneBy(['leMedecin' =>$leMedecin,'leJour' => $leJour]);
            $dateDebut = $rendezVous->getDateDebut()->format('H:i:s');
                if($dateDebut<$lesHorairesDuMedecin->getHeureMatinDebut()->format('H:i:s') || ($dateDebut>$lesHorairesDuMedecin->getHeureMatinFin()->format('H:i:s') && $dateDebut<$lesHorairesDuMedecin->getHeureApremDebut()->format('H:i:s')) || $dateDebut>$lesHorairesDuMedecin->getHeureApremFin()->format('H:i:s')){
                    $error="Le médecin ne prends pas de rendez-vous sur ces horaires";
                }
        //test si le formulaire est bon et si le rendez-vous a remplit les conditions, envoit dans la bdd
        if($form->isSubmitted() && $form->isValid() && !$error){
            $lePatient = $this->security->getUser();
            $rendezVous->setLeMedecin($leMedecin);
            $rendezVous->setLePatient($lePatient);
            $manager->persist($rendezVous);
            $manager->flush();
        }
        //rechargement des rendez-vous pour avoir le nouveau dans le calendrier
        $lesRendezVous = $rendezVousRepository->findBy(['leMedecin' => $leMedecin]);
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
        return $this->render('medecin_page/index.html.twig', [
            'medecin' => $leMedecin,
            'rdv' => $rdvJson,
            'form' => $form->createView(),
            'error' => $error
        ]);
    }
}
