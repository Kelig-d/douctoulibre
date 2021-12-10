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

    #[Route('/medecin/page/{medecinId}', name: 'medecin_page')]
    public function index(int $medecinId, MedecinRepository $medecinRepository, RendezVousRepository $rendezVousRepository): Response
    {
        //Récupération du médecin et de ses rendez vous
        $leMedecin = $medecinRepository->findOneBy(['id' => $medecinId]);
        $lesRendezVous = $rendezVousRepository->findBy(['leMedecin' => $leMedecin]);
        //rechargement des rendez-vous pour avoir le nouveau dans le calendrier
        $lesRendezVous = $rendezVousRepository->findBy(['leMedecin' => $leMedecin]);
        //création d'un tableau des rendez-vous pour le transformer en json pour le full calendar
        $rdv = array();
        foreach($lesRendezVous as $unRendezVous){
            //vérification si le rendez-vous est validé ou non par le médecin
            if($unRendezVous->getDuree()){
                $duree = $unRendezVous->getDuree();
            }
            else{
                $duree = 60;
            }
            //création de la date de fin du rendez vous pour l'afficher correctement dans le calendrier, 1h de temps par défaut
            $dateFin = new DateTime($unRendezVous->getDateDebut()->format(('Y-m-d H:i:s')));
            $dateFin->add(new DateInterval('PT'. $duree. 'M'));
            $titre = "Rdv déjà réservé";
            $background = "#EFA111";
            $rdv[] = [
                'id' => $unRendezVous->getId(),
                'start' => $unRendezVous->getDateDebut()->format('Y-m-d H:i:s'),
                'end' => $dateFin->format('Y-m-d H:i:s'),
                'title' => $titre,
                'backgroundColor' => $background
            ];
        }
        //encodage en json des rendez-vous
        $rdvJson = json_encode($rdv);
        return $this->render('medecin_page/index.html.twig', [
            'medecin' => $leMedecin,
            'rdv' => $rdvJson,
        ]);
    }
}
