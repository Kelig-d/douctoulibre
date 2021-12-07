<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Repository\PatientRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
        /**
     * @var Security
     */
    private $security;
        public function __construct(Security $security)
    {
       $this->security = $security;
    }
    #[Route('/', name: 'home')]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        if($this->security->getUser()){
        if($this->security->getUser()->getRoles()[0] =="ROLE_PATIENT"){
            $lesRendezVous = $rendezVousRepository->findBy(['lePatient' => $this->security->getUser()]);
            return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'lesRendezVous' => $lesRendezVous
        ]);
        }
        if($this->security->getUser()->getRoles()[0] =="ROLE_MEDECIN"){
            $lesRendezVousABloquer = $rendezVousRepository->findBy(['leMedecin' => $this->security->getUser(), 'duree' =>null]);
            return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'lesRendezVousABloquer' => $lesRendezVousABloquer
        ]);
        }
    }
                return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
