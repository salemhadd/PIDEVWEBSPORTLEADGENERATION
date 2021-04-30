<?php

namespace App\Controller;

use App\Entity\EmploiDuTemps;
use App\Entity\Seance;
use phpDocumentor\Reflection\Types\String_;
use App\Form\EmploiDuTempsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use DateTime;
/**
 * @Route("/emploi/du/temps")
 */
class EmploiDuTempsController extends AbstractController
{
    /**
     * @Route("/", name="emploi_du_temps_index", methods={"GET"})
     */

    public function index(Request $request): Response
    {
        $var = $request->query->get('users');
        if ($var != "") {
            $query = $this->getDoctrine()->getRepository(EmploiDuTemps::class)->createQueryBuilder('u');
            $query->where('u.idemploi LIKE :title')
                ->setParameter("title", "%$var%")
                ->getQuery();


            $emploiDuTemps = $query->getQuery()->getResult();

        } else {
            $emploiDuTemps = $this->getDoctrine()
                ->getRepository(EmploiDuTemps::class)
                ->findAll();
        }

        return $this->render('emploi_du_temps/index.html.twig', [
            'emploi_du_temps' => $emploiDuTemps,
        ]);
    }



    public function recherche(Request $request):response
    {
        $type = $request->request->get('request');
        if ($type!="")
        {

            $query =$this->getDoctrine()->getRepository(EmploiDuTemps::class)->createQueryBuilder('u');
            $query->where('u.idemploi LIKE :title')
                ->setParameter("title","%libelle%")
                ->getQuery();


            $emploiDuTemps = $query->getQuery()->getResult();

        }
        else
        {
            $type = $this->getDoctrine()
                ->getRepository(EmploiDuTemps::class)
                ->findAll();
        }


        return $this->json(['emploi_du_temps' => $emploiDuTemps]);
    }

    /**
     * @Route("/new", name="emploi_du_temps_new", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy): Response
    {
        $emploiDuTemp = new EmploiDuTemps();
        $form = $this->createForm(EmploiDuTempsType::class, $emploiDuTemp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emploiDuTemp);
            $entityManager->flush();
            $flashy->success('Emploi du temps ajouté!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('emploi_du_temps_index');
        }

        return $this->render('emploi_du_temps/new.html.twig', [
            'emploi_du_temp' => $emploiDuTemp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idemploi}", name="emploi_du_temps_show", methods={"GET"})
     */
    public function show(EmploiDuTemps $emploiDuTemp): Response
    {
        return $this->render('emploi_du_temps/show.html.twig', [
            'emploi_du_temp' => $emploiDuTemp,

        ]);
    }

    /**
     * @Route("/{idemploi}/edit", name="emploi_du_temps_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EmploiDuTemps $emploiDuTemp,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(EmploiDuTempsType::class, $emploiDuTemp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('Emploi du temps modifié!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('emploi_du_temps_index');
        }

        return $this->render('emploi_du_temps/edit.html.twig', [
            'emploi_du_temp' => $emploiDuTemp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idemploi}", name="emploi_du_temps_delete", methods={"POST"})
     */
    public function delete(Request $request, EmploiDuTemps $emploiDuTemp,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emploiDuTemp->getIdemploi(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emploiDuTemp);
            $entityManager->flush();
            $flashy->success('Emploi du temps supprimé!', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('emploi_du_temps_index');
    }
}