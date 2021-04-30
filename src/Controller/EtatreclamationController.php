<?php

namespace App\Controller;

use App\Entity\Etatreclamation;
use App\Form\EtatreclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etatreclamation")
 */
class EtatreclamationController extends AbstractController
{
    /**
     * @Route("/", name="etatreclamation_index", methods={"GET"})
     */
    public function index(): Response
    {
        $etatreclamations = $this->getDoctrine()
            ->getRepository(Etatreclamation::class)
            ->findAll();

        return $this->render('etatreclamation/index.html.twig', [
            'etatreclamations' => $etatreclamations,
        ]);
    }

    /**
     * @Route("/new", name="etatreclamation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $etatreclamation = new Etatreclamation();
        $form = $this->createForm(EtatreclamationType::class, $etatreclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($etatreclamation);
            $entityManager->flush();

            return $this->redirectToRoute('etatreclamation_index');
        }

        return $this->render('etatreclamation/new.html.twig', [
            'etatreclamation' => $etatreclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etatreclamation_show", methods={"GET"})
     */
    public function show(Etatreclamation $etatreclamation): Response
    {
        return $this->render('etatreclamation/show.html.twig', [
            'etatreclamation' => $etatreclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etatreclamation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etatreclamation $etatreclamation): Response
    {
        $form = $this->createForm(EtatreclamationType::class, $etatreclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etatreclamation_index');
        }

        return $this->render('etatreclamation/edit.html.twig', [
            'etatreclamation' => $etatreclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etatreclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Etatreclamation $etatreclamation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etatreclamation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($etatreclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etatreclamation_index');
    }
}
