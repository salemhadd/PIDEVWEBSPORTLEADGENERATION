<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Zonedacces;
use App\Form\ZonedaccesType;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Options;
use Dompdf\Dompdf;

/**
 * @Route("/zonedacces")
 */
class ZonedaccesController extends AbstractController
{
    /**
     * @Route("/", name="zonedacces_index", methods={"GET"})
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


        return $this->render('zonedacces/index.html.twig', [
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

        $html = $this->renderView('zonedacces/index.html.twig', [
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


        return $this->render('zonedacces/index.html.twig', [
            'zonedacce' => $zonedacces,
        ]);


    }

    /**
     * @Route("/new", name="zonedacces_new", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy): Response
    {
        $zonedacce = new Zonedacces();
        $form = $this->createForm(ZonedaccesType::class, $zonedacce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($zonedacce);
            $entityManager->flush();
            $flashy->success('Zone ajoutée!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('zonedacces_index');
        }

        return $this->render('zonedacces/new.html.twig', [
            'zonedacce' => $zonedacce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idzone}", name="zonedacces_show", methods={"GET"})
     */
    public function show(Zonedacces $zonedacce): Response
    {
        return $this->render('zonedacces/show.html.twig', [
            'zonedacce' => $zonedacce,
        ]);
    }

    /**
     * @Route("/{idzone}/edit", name="zonedacces_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Zonedacces $zonedacce,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(ZonedaccesType::class, $zonedacce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('Zone modifiée!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('zonedacces_index');
        }

        return $this->render('zonedacces/edit.html.twig', [
            'zonedacce' => $zonedacce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idzone}", name="zonedacces_delete", methods={"POST"})
     */
    public function delete(Request $request, Zonedacces $zonedacce,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zonedacce->getIdzone(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($zonedacce);
            $entityManager->flush();
            $flashy->success('Zone Supprimée!', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('zonedacces_index');
    }
}
