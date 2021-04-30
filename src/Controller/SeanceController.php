<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use DateTime;
/**
 * @Route("/seance")
 */
class SeanceController extends AbstractController
{
    /**
     * @Route("/", name="seance_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $var = $request->query->get('users');
        if ($var != "") {
            $query = $this->getDoctrine()->getRepository(Seance::class)->createQueryBuilder('u');
            $query->where('u.idseance LIKE :title')
                ->setParameter("title", "%$var%")
                ->getQuery();


            $seance = $query->getQuery()->getResult();

        } else {
            $seance = $this->getDoctrine()
                ->getRepository(Seance::class)
                ->findAll();
        }

        return $this->render('seance/index.html.twig', [
            'seance' => $seance
        ]);}
    /**
         * @param Seance|null $calender
         * @param Request $request
         * @return Response
         * @throws \Exception
         * @Route ("/api/{idseance}/edit",name="api",methods={"PUT"})
         */
    public function api(?Seance $calender,Request $request){
    $donnes=json_decode($request->getContent());


    if(
        isset($donnes->dates) && isset($donnes->capacite) && isset($donnes->activiteid)
    ){
        $code=200;
        if(!$calender){
            $calender= new Seance();
            $code=201;
        }
        $calender->setDates(new DateTime($donnes->dates));
        $calender->setCapacite($donnes->capacite);
        $calender->setActiviteid($donnes->activiteid);

        // $calender->setMail($donnes->mail);


        $em=$this->getDoctrine()->getManager();
        $em->persist($calender);
        $em->flush();


        return new Response('ok',$code);
    }else{
        return new Response('Data missed',404);
    }
    return $this->render('seance/calender.html.twig', [
        'controller_name' => 'SeanceController',
    ]);
    }

    /**
     * @Route ("/calender",name="calender")
     */
    public function calender():Response{
        $events = $this->getDoctrine()
            ->getRepository(Seance::class)
            ->findAll();
        $rdvs =[];
        foreach ($events as $event){
            $rdvs[]=[
              'id'=>$event->getIdSeance(),
              'dates'=>$event->getDates()->format('Y-m-d H:i:s'),
              'capacite'=>$event->getCapacite(),
              'activiteid'=>$event->getActiviteid(),

            ];
        }
        $data= json_encode($rdvs);
        return $this->render('seance/calender.html.twig',compact('data'));
    }
    /**
     * @Route("/recherche", name="recherche", methods={"POST", "GET"})
     */
    public function recherche(Request $request):response
    {
        $type = $request->request->get('request');
        if ($type!="")
        {$query =$this->getDoctrine()->getRepository(Seance::class)->createQueryBuilder('u');
            $query->where('u.idseance LIKE :title')
                ->setParameter("title","%libelle%")
                ->getQuery();


            $Seance = $query->getQuery()->getResult();

        }
        else
        {
            $type = $this->getDoctrine()
                ->getRepository(Seance::class)
                ->findAll();
        }


        return $this->json(['Seance' => $Seance]);
    }


    /**
     * @Route("/new", name="seance_new", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seance);
            $entityManager->flush();
            $flashy->success('Seance ajoutée!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('seance_index');
        }

        return $this->render('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idseance}", name="seance_show", methods={"GET"})
     */
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/{idseance}/edit", name="seance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seance $seance,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('Seance modifiée!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('seance_index');
        }

        return $this->render('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idseance}", name="seance_delete", methods={"POST"})
     */
    public function delete(Request $request, Seance $seance,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getIdseance(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
            $flashy->success('Seance supprimée!', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('seance_index');
    }
}