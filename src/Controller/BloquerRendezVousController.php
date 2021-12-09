<?php

namespace App\Controller;

use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BloquerRendezVousController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
        public function __construct(Security $security)
    {
       $this->security = $security;
    }
    #[Route('/bloquer/rendez/vous/', name: 'bloquer_rendez_vous')]
    public function index(): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('resultBloquage'))
            ->add('duree')
            ->add('id')
            ->getForm();
        return $this->render('bloquer_rendez_vous/index.html.twig', [
            'controller_name' => 'BloquerRendezVousController',
            'form' =>$form->createView()
        ]);
    }

    #[Route('/bloquer/rendez/vous/result', name:'resultBloquage')]
    public function resultBloquage(RendezVousRepository $rendezVousRepository, EntityManagerInterface $manager,  Request $request){
        $id = $request->request->get('form')['id'];
        $duree = $request->request->get('form')['duree'];
        if($id && $duree) {
            $rendezVous = $rendezVousRepository->findOneBy(['id' => $id]);
            $rendezVous->setDuree($duree);
            $manager->persist($rendezVous);
            $manager->flush();
        }
            return $this->redirect($this->generateUrl('home'));
    }
}
