<?php

namespace App\Controller;

use App\Repository\MedecinRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }
    /**
     * Fonction d'affichage d'un champ de recherche
     */
    #[Route('/search', name: 'search')]
    public function searchBar()
    {
        //Création du formulaire qui redirigera vers une seconde fonction
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control me-2',
                    'placeholder' => 'Entrez ce que vous voulez'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary research-button'
                ]
            ])
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     * Fonction permettant d'effectuer la recherche grâce à une requête personnalisée dans le repository
     */
    public function handleSearch(Request $request, MedecinRepository $medecinRepository)
    {
        $query = $request->request->get('form')['query'];
        if($query) {
            $medecins = $medecinRepository->findBySearch($query);
        }
        return $this->render('search/index.html.twig', [
            'medecins' => $medecins
        ]);
    }
}
