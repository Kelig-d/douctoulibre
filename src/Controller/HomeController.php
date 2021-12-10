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
     * On crée une variable security qui va contenir les informations de l'utilisateur connecté, les tokens,...
     */
    private $security;
        public function __construct(Security $security)
    {
       $this->security = $security;
    }
    #[Route('/', name: 'home')]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        //On teste si l'utilisateur est connecté
        if($this->security->getUser()){
            //On regarde quel type d'ulisateur c'est et on envoies les infos selon le résultat au twig
            if($this->security->getUser()->getRoles()[0] =="ROLE_PATIENT"){
                $lesRendezVous = $rendezVousRepository->findBy(['lePatient' => $this->security->getUser()]);
                return $this->render('home/index.html.twig', [
                    'lesRendezVous' => $lesRendezVous
            ]);
            }
            if($this->security->getUser()->getRoles()[0] =="ROLE_MEDECIN"){
                return $this->render('home/index.html.twig', [
            ]);
            }
        }
        return $this->render('home/index.html.twig', [
        ]);
    }
}
