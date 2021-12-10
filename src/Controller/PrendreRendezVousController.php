<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Repository\JourRepository;
use App\Form\PrendreRendezVousType;
use App\Repository\MedecinRepository;
use App\Repository\HorairesRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PrendreRendezVousController extends AbstractController
{
    #[Route('/prendre/rendez/vous', name: 'prendre_rendez_vous')]
    public function index(int $medecinId, MedecinRepository $medecinRepository, RendezVousRepository $rendezVousRepository,HorairesRepository $horairesRepository,JourRepository $jourRepository, Request $request, EntityManagerInterface $manager): Response
    {
        //création d'une variable erreur qui va afficher le problème à l'utilisateur s'il y en a un
        $error ="";
        //Récupération du médecin et de ses rendez vous
        $leMedecin = $medecinRepository->findOneBy(['id' => $medecinId]);
        $lesRendezVous = $rendezVousRepository->findBy(['leMedecin' => $leMedecin]);
        //formulaire pour ajouter un rendez-vous
        $rendezVous = new RendezVous();
        $form = $this->createForm(PrendreRendezVousType::class, $rendezVous);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
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
            if(!$error){
                $lePatient = $this->security->getUser();
                $rendezVous->setLeMedecin($leMedecin);
                $rendezVous->setLePatient($lePatient);
                $manager->persist($rendezVous);
                $manager->flush();
            }
        }
        return $this->render('prendre_rendez_vous/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }
}
