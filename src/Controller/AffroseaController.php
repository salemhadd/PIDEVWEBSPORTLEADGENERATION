<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\Seance1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/affrosea")
 */
class AffroseaController extends AbstractController
{
    /**
     * @Route("/", name="affrosea_index", methods={"GET"})
     */
    public function index(): Response
    {
        $seances = $this->getDoctrine()
            ->getRepository(Seance::class)
            ->findAll();

        return $this->render('affrosea/index.html.twig', [
            'seances' => $seances,
        ]);
    }
    /**
     * @Route("/imprimer", name="affrosea_imprimer", methods={"GET"})
     */
    public function imprimer(): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $seances = $this->getDoctrine()
            ->getRepository(Seance::class)
            ->findAll();



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('affrosea/imprimer.html.twig', [
            'seances' => $seances,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);


    }
    /**
     * @Route("/new", name="affrosea_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $seance = new Seance();
        $form = $this->createForm(Seance1Type::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seance);
            $entityManager->flush();

            return $this->redirectToRoute('affrosea_index');
        }

        return $this->render('affrosea/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idseance}", name="affrosea_show", methods={"GET"})
     */
    public function show(Seance $seance): Response
    {
        return $this->render('affrosea/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/{idseance}/edit", name="affrosea_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seance $seance): Response
    {
        $form = $this->createForm(Seance1Type::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('affrosea_index');
        }

        return $this->render('affrosea/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idseance}", name="affrosea_delete", methods={"POST"})
     */
    public function delete(Request $request, Seance $seance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getIdseance(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('affrosea_index');
    }

}
