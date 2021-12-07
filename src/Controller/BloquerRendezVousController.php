<?php

namespace App\Controller;

use App\Form\BloquerRendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BloquerRendezVousController extends AbstractController
{
    #[Route('/bloquer/rendez/vous/{rendezVousId}', name: 'bloquer_rendez_vous')]
    public function index(int $rendezVousId, RendezVousRepository $rendezVousRepository, EntityManagerInterface $manager,  Request $request): Response
    {
        $rendezVous = $rendezVousRepository->findOneBy(['id' => $rendezVousId]);
        $form = $this->createForm(BloquerRendezVousType::class, $rendezVous);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($rendezVous);
            $manager->flush();
            return $this->redirect($this->generateUrl('home'));
        }
        return $this->render('bloquer_rendez_vous/index.html.twig', [
            'controller_name' => 'BloquerRendezVousController',
            'form' =>$form->createView()
        ]);
    }
}
