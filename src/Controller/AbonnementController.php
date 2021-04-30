<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Form\AbonnementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Dompdf\Options;
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


/**
 * @Route("/abonnement")
 */
class AbonnementController extends AbstractController
{
    /**
     * @Route("/", name="abonnement_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $var = $request->query->get('users');
        if ($var != "") {
            $query = $this->getDoctrine()->getRepository(Abonnement::class)->createQueryBuilder('u');
            $query->where('u.type LIKE :title')
                ->setParameter("title", "%$var%")
                ->getQuery();


            $abonnements = $query->getQuery()->getResult();

        } else {
            $abonnements = $this->getDoctrine()
                ->getRepository(Abonnement::class)
                ->findAll();
        }

        $cv=0;
        $cn=0;
        $ce=0;
        $cs=0;
        foreach ($abonnements as $abonnement) {
            if ($abonnement->getType()=="GOLD"){
                $cv+=$abonnement->getPrix();
            }else if ($abonnement->getType()=="new"){
                $cn+=$abonnement->getPrix();
            }else if ($abonnement->getType()=="premium"){
                $ce+=$abonnement->getPrix();
            }else{
                $cs+=$abonnement->getPrix();
            }
        }
        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnements,
            'cv' => $cv,
            'cn' => $cn,
            'ce' => $ce,
            'cs' => $cs
        ]);
    }
    /**
     * @Route("/recherche", name="recherche", methods={"POST", "GET"})
     */
    public function recherche(Request $request):response
    {
        $type = $request->request->get('request');
        if ($type!="")
        {

            $query =$this->getDoctrine()->getRepository(Abonnement::class)->createQueryBuilder('u');
            $query->where('u.type LIKE :title')
                ->setParameter("title","%type%")
                ->getQuery();


            $Abonnement = $query->getQuery()->getResult();

        }
        else
        {
            $type = $this->getDoctrine()
                ->getRepository(Abonnement::class)
                ->findAll();
        }


        return $this->json(['Abonnements' => $Abonnement]);
    }


    /**
     * @Route("/generatePDF", name="generatePDF", methods={"POST", "GET"})
     */
    public function generatePDF(): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $Abonnement = $this->getDoctrine()
            ->getRepository(Abonnement::class)
            ->findAll();

        $html = $this->renderView('abonnement/imprimer.html.twig', [
            'abonnements' => $Abonnement,
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
     * @Route("/new", name="abonnement_new", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($abonnement);
            $entityManager->flush();
            $flashy->success('Abonnement ajouté!', 'http://your-awesome-link.com');
            $mail = new PHPMailer(true);

            try {

                $prix= $form->get('prix')->getData();
                $type = $form->get('type')->getData();
                $activite = $form->get('activite')->getData();


                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'oumaymabrayek16@gmail.com';             // SMTP username
                $mail->Password   = 'oumayma123';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('oumaymabrayek16@gmail.com', 'Hand Clasper');

                // Add a recipient
                // Content
                $corps="Bonjour Monsieur/Madame  un nouvel abonnement a ete ajouté: ".$prix. "  la date ".$type." les activités sont comme suit: " .$activite ;
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Abonnement ajouté!';
                $mail->Body    = $corps;
                $mail->addAddress("oumaymabrayek16@gmail.com");

                $mail->send();
                $this->addFlash('message','the email has been sent');

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            return $this->redirectToRoute('abonnement_index');
        }

        return $this->render('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="abonnement_show", methods={"GET"})
     */
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="abonnement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Abonnement $abonnement,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('Abonnement modifié!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('abonnement_index');
        }

        return $this->render('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="abonnement_delete", methods={"POST"})
     */
    public function delete(Request $request, Abonnement $abonnement,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($abonnement);
            $entityManager->flush();
            $flashy->success('Abonnement supprimé!', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('abonnement_index');
    }
}
