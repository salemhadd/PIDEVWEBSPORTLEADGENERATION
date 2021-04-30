<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request,\Swift_Mailer $mailer): Response
    {
        $form =$this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            //ici nous enverrons le mail
            $message =(new \Swift_Message('mail'))
                //on attribue l expediteur
                    ->setFrom($contact['email'])
                //on attribue l expediteur
                    ->setTo('rania.benabdallah@esprit.tn')
                //on crée le message avec la vue twig
                    ->setBody(
                    $contact['message']
                        );


            //on envoie le message
            $mailer->send($message);
            $this->addFlash('message','le message a bien été envoyé');
           // return $this->redirectToRoute('/contact');

        }
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
