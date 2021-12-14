<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Entity\Patient;
use App\Form\RegistrationFormMedecin;
use App\Form\RegistrationFormPatient;
use App\Repository\SpecialiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    /**
     * Fonction générée de symfony pour créer un utilisateur
     */
    #[Route('/register/{type}', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, string $type, SpecialiteRepository $specialiteRepository): Response
    {
        /**
         * On va tester si le formulaire d'inscription renvoies le type patient ou le type docteur pour créer la bonne classe héritée
         */
        if($type == "Patient"){
            $user = new Patient();
            $user->setRoles(['ROLE_PATIENT']);
            $form = $this->createForm(RegistrationFormPatient::class, $user);
        }
        elseif($type == "Medecin"){
            $user = new Medecin();
            $user->setRoles(['ROLE_MEDECIN']);
            $form = $this->createForm(RegistrationFormMedecin::class, $user);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'type' => $type,
        ]);
    }
}
