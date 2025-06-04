<?php
// src/Controller/ContactController.php
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // 1. Nouvelle instance de l’entité
        $contact = new Contact();

        // 2. Création du formulaire lié à cette entité
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        // 3. À la soumission valide, on persiste et flush en base
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Votre message a bien été envoyé, nous vous recontacterons bientôt.');

            return $this->redirectToRoute('app_contact');
        }

        // 4. Affichage du formulaire
        return $this->render('academy/academy.html.twig', [
            'contactForm' => $form->createView(),
            'headerMode'  => 'gris',
        ]);
    }
}
