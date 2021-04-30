<?php

namespace App\Controller;

use App\Entity\TypeReclamation;
use App\Form\TypeReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/reclamation")
 */
class TypeReclamationController extends AbstractController
{
    /**
     * @Route("/", name="type_reclamation_index", methods={"GET"})
     */
    public function index(): Response
    {
        $typeReclamations = $this->getDoctrine()
            ->getRepository(TypeReclamation::class)
            ->findAll();

        return $this->render('type_reclamation/index.html.twig', [
            'type_reclamations' => $typeReclamations,
        ]);
    }

    /**
     * @Route("/new", name="type_reclamation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeReclamation = new TypeReclamation();
        $form = $this->createForm(TypeReclamationType::class, $typeReclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeReclamation);
            $entityManager->flush();

            return $this->redirectToRoute('type_reclamation_index');
        }

        return $this->render('type_reclamation/new.html.twig', [
            'type_reclamation' => $typeReclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_reclamation_show", methods={"GET"})
     */
    public function show(TypeReclamation $typeReclamation): Response
    {
        return $this->render('type_reclamation/show.html.twig', [
            'type_reclamation' => $typeReclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_reclamation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeReclamation $typeReclamation): Response
    {
        $form = $this->createForm(TypeReclamationType::class, $typeReclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_reclamation_index');
        }

        return $this->render('type_reclamation/edit.html.twig', [
            'type_reclamation' => $typeReclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeReclamation $typeReclamation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeReclamation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeReclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_reclamation_index');
    }
}
