<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Activite;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\SearchForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class FrontController extends AbstractController
{
    /**
     * @Route("/front", name="front")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }


    /**
     * @Route("/afficheact", name="liste_activites", methods={"GET"})
     */
    public function afficheractivites(Request $request): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page',1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $activites = $this->getDoctrine()
            ->getRepository(Activite::class)
            ->findSearch($data);

        return $this->render('activite/afficher.html.twig', [
            'activites' => $activites,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/newfront", name="reclamation_newfront", methods={"GET","POST"})
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function newfront(Request $request,\Swift_Mailer $mailer): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            /*$email = (new Email())
                ->from('karboulomar@gmail.com')
                ->to('omar.karboul@esprit.tn')
                ->text("votre réclamation a été bien enregistrer {$reclamation->getContenu()}");
            $mailer->send($email);*/

            $message =(new \Swift_Message('mail'))
                //on attribue l expediteur
                ->setFrom('salem.haddad@esprit.tn')
                //on attribue l expediteur
                ->setTo('salem.haddad1998@gmail.com')
                //on crée le message avec la vue twig
                ->setBody('
                    bojour'
                );


            //on envoie le message
            $mailer->send($message);
            $this->addFlash('message','le message a bien été envoyé');
            // return $this->redirectToRoute('/contact');

            return $this->redirectToRoute('admin');
        }

        return $this->render('reclamation/newfront.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/listeactivites", name="liste_activitespdf", methods={"GET"})
     */
    public function listeactivites(Request $request): Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont','Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $activites = $this->getDoctrine()
            ->getRepository(Activite::class)
            ->findAll();



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('activite/listact.html.twig', [
            'activites' => $activites,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);



    }

    /**
     * @Route("/mail", name="maile")
     */
    public function mail(MailerInterface $mailer){
        /*$message= (new \Swift_Message('nouvelle réclamation'))
            ->setFrom('omarnakech@gmail.com')
            ->setTo('karboulomar@gmail.com')
            ->setBody('test','text/plain');
        dd($message);
        $mailer->send($message);
        $this->addFlash('message','le message a été envoyé');*/
        $email = (new Email())
                ->from('karboulomar@gmail.com')
                ->to('omar.karboul@esprit.tn')
                ->text("votre réclamation a été bien enregistrer");
            $mailer->send($email);

        return new Response('eeef');
    }
}
