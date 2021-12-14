<?php

namespace App\Controller;

use App\Entity\Horaires;
use App\Entity\RendezVous;
use App\Form\CreerHorairesType;
use App\Repository\HorairesRepository;
use App\Repository\JourRepository;
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
    public function index(RendezVousRepository $rendezVousRepository, HorairesRepository $horairesRepository, Request $request, JourRepository $jourRepository, EntityManagerInterface $manager): Response
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
                //On récupère les horaires et on vérifie si le médecin a bien les horaires pour tous les jours
                $lesHoraires = $horairesRepository->findBy(['leMedecin' => $this->security->getUser()]);
                if(count($lesHoraires)>=7) {
                    return $this->render('home/index.html.twig', [
                    ]);
                }
                else {
                    //S'il manque des horaires, on génère un formulaire pour les ajouter
                    //jour correspond au jour manquant
                    $jour = $jourRepository->findAll()[count($lesHoraires)];
                    $horaire = new Horaires();
                    $form = $this->createForm(CreerHorairesType::class, $horaire);
                    $form->handleRequest($request);
                    if($form->isSubmitted() && $form->isValid() ){
                        //On vérifie si la case jour non travaillé est coché pour créer un horaire adéquat
                        if(!$form->get('jourNonTravaille')->getData()){
                            //On teste si l'écart entre les horaires est valide
                            if($horaire->getHeureApremFin()->format('H')-$horaire->getHeureApremDebut()->format('H') <1 || $horaire->getHeureMatinFin()->format('H') - $horaire->getHeureMatinDebut()->format('H')<1){
                            return $this->render('home/index.html.twig',[
                                "error" => "Vous devez choisir des horaires valides",
                                "form" => $form->createView(),
                                "jour" => $jour->getJour(),
                            ]);
                            }
                            //Création des horaires
                            $horaire->setLeMedecin($this->security->getUser());
                            $horaire->setLeJour($jour);
                            $manager->persist($horaire);
                            $manager->flush();
                        }
                        else{
                            //Création d'un horaire "vide" si le médecin ne travaille pas
                            $horaire->setHeureMatinDebut(new \DateTime('2000-01-01 00:00:00'));
                            $horaire->setHeureMatinFin(new \DateTime('2000-01-01 00:00:00'));
                            $horaire->setHeureApremDebut(new \DateTime('2000-01-01 00:00:00'));
                            $horaire->setHeureApremFin(new \DateTime('2000-01-01 00:00:00'));
                            $horaire->setLeJour($jour);
                            $horaire->setLeMedecin($this->security->getUser());
                            $manager->persist($horaire);
                            $manager->flush();
                        }
                    }
                    return $this->render('home/index.html.twig', [
                        'form' => $form->createView(),
                        'jour' => $jour->getJour(),
                    ]);
                }
            }
        }
        return $this->render('home/index.html.twig', [
        ]);
    }
}
