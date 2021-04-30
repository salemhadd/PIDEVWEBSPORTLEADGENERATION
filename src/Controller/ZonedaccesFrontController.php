<?php

namespace App\Controller;

use App\Entity\Zonedacces;
use App\Form\Zonedacces1Type;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ZonedaccesFrontController extends AbstractController
{
    /**
     * @Route("/Zonefront", name="zonedacces_front_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $var = $request->query->get('users');
        if ($var != "") {
            $query = $this->getDoctrine()->getRepository(Zonedacces::class)->createQueryBuilder('u');
            $query->where('u.nom LIKE :title')
                ->setParameter("title", "%$var%")
                ->getQuery();


            $zonedacces = $query->getQuery()->getResult();

        } else {
            $zonedacces = $this->getDoctrine()
                ->getRepository(Zonedacces::class)
                ->findAll();
        }



        return $this->render('zonedacces_front/index.html.twig', [
            'zonedacces' => $zonedacces,
        ]);
    }

    /**
     * @Route("/recherche", name="recherche", methods={"POST", "GET"})
     */
    public function recherche(Request $request):response
    {
        $nom = $request->request->get('request');
        if ($nom!="")
        {

            $query =$this->getDoctrine()->getRepository(Zonedacces::class)->createQueryBuilder('u');
            $query->where('u.nom LIKE :title')
                ->setParameter("title","%nom%")
                ->getQuery();


            $Zonedacces = $query->getQuery()->getResult();

        }
        else
        {
            $type = $this->getDoctrine()
                ->getRepository(Zonedacces::class)
                ->findAll();
        }


        return $this->json(['Zonedacces' => $Zonedacces]);
    }


    /**
     * @Route("/generatePDF", name="generatePDF", methods={"POST", "GET"})
     */
    public function generatePDF(): Response
    {
        $em=$this->getDoctrine()->getManager();

        $zonedacces=$em->getRepository(Zonedacces::class)->findAll();


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('zonedacces_front/imprimer.html.twig', [
            'zonedacces' => $zonedacces,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Les zones d'acces", ["Attachment" => true]);


        return $this->render('zonedacces_front/imprimer.html.twig', [
            'zonedacce' => $zonedacces,
        ]);


    }

}
