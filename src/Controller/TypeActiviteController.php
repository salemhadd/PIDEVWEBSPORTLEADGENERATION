<?php

namespace App\Controller;

use App\Entity\TypeActivite;
use App\Form\TypeActiviteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/activite")
 */
class TypeActiviteController extends AbstractController
{
    /**
     * @Route("/", name="type_activite_index", methods={"GET"})
     */
    public function index(): Response
    {
        $typeActivites = $this->getDoctrine()
            ->getRepository(TypeActivite::class)
            ->findAll();

        return $this->render('type_activite/index.html.twig', [
            'type_activites' => $typeActivites,
        ]);
    }


    /**
     * @Route("/new", name="type_activite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeActivite = new TypeActivite();
        $form = $this->createForm(TypeActiviteType::class, $typeActivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeActivite);
            $entityManager->flush();

            return $this->redirectToRoute('type_activite_index');
        }

        return $this->render('type_activite/new.html.twig', [
            'type_activite' => $typeActivite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_activite_show", methods={"GET"})
     */
    public function show(TypeActivite $typeActivite): Response
    {
        return $this->render('type_activite/show.html.twig', [
            'type_activite' => $typeActivite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_activite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeActivite $typeActivite): Response
    {
        $form = $this->createForm(TypeActiviteType::class, $typeActivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_activite_index');
        }

        return $this->render('type_activite/edit.html.twig', [
            'type_activite' => $typeActivite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_activite_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeActivite $typeActivite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeActivite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeActivite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_activite_index');
    }


}
