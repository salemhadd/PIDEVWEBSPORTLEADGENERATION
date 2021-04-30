<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\EtatType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Etat")
 */
class EtatController extends AbstractController
{
    /**
     * @Route("/h", name="etat_index", methods={"GET"})
     */
    public function index(): Response
    {
        $etats = $this->getDoctrine()
            ->getRepository(Etat::class)
            ->findAll();

        return $this->render('etat/index.html.twig', [
            'etats' => $etats,
        ]);
    }
    /**
     * @Route("Statstique", name="Statstique", methods={"GET"})
     */
    public function teststats () :Response{
        $Etat = $this->getDoctrine()
            ->getRepository(Etat::class)
            ->findAll();

        $data = [];
        $pieChart = new PieChart();

        foreach ($Etat as $Etat) {
            $typeEtat= $Etat->gettypeEtat();
            $typeCount=(int) count($typeEtat->getMateriel);
            array_push($data , [$typeEtat,$typeCount]);

        }

        $pieChart->getData()->setArrayToDataTable(
            $data,true
        );

        $pieChart->getOptions()->setTitle('nombre d etat par type');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('etat/Statstique.html.twig',array('piechart' => $pieChart));
    }

    /**
     * @Route("/new", name="etat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $etat = new Etat();
        $form = $this->createForm(EtatType::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($etat);
            $entityManager->flush();

            return $this->redirectToRoute('etat_index');
        }

        return $this->render('etat/new.html.twig', [
            'etat' => $etat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etat_show", methods={"GET"})
     */
    public function show(Etat $etat): Response
    {
        return $this->render('etat/show.html.twig', [
            'etat' => $etat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etat $etat): Response
    {
        $form = $this->createForm(EtatType::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etat_index');
        }

        return $this->render('etat/edit.html.twig', [
            'etat' => $etat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etat_delete", methods={"POST"})
     */
    public function delete(Request $request, Etat $etat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($etat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etat_index');
    }


    public function teststats2 () :Response{
        $typeActivites = $this->getDoctrine()
            ->getRepository(TypeActivite::class)
            ->findAll();

        $data = [];
        $pieChart = new PieChart();

        foreach ($typeActivites as $typeActivite) {
            $typeNom = $typeActivite->gettype();
            $typeCount=(int) count($typeActivite->getActivites());
            array_push($data , [$typeNom,$typeCount]);

        }

        $pieChart->getData()->setArrayToDataTable(
            $data,true
        );

        $pieChart->getOptions()->setTitle('nombre d activité par type');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('type_activite/teststats.html.twig',array('piechart' => $pieChart));
    }
}
