<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Form\Abonnement1Type;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Options;
use Dompdf\Dompdf;


class AbonnementFrontController extends AbstractController
{
    /**
     * @Route("/AbonnementFront", name="abonnement_front_index", methods={"GET"})
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
        }$var = $request->query->get('users');
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



        return $this->render('abonnement_front/index.html.twig', [
            'abonnements' => $abonnements,
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
                ->setParameter("title","%libelle%")
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
     * @Route("/generatePDF1", name="generatePDF1", methods={"POST", "GET"})
     */
    public function generatePDF1(): Response
    {
        $em=$this->getDoctrine()->getManager();

        $Abonnement=$em->getRepository(Abonnement::class)->findAll();


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        //$pdfOptions->set('isRemoteEnabled' , 'true');

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('abonnement_front/imprimer.html.twig', [
            'abonnements' => $Abonnement,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Les abonnements", ["Attachment" => true]);


        return $this->render('abonnement_front/imprimer.html.twig', [
            'abonnements' => $Abonnement,
        ]);


    }


}
