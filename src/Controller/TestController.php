<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Etatreclamation;
use App\Entity\TypeActivite;
use App\Entity\TypeReclamation;
use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

class TestController extends AbstractController
{
    /**
     * @Route("/backadmintest", name="test")
     */
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("statstypeact", name="statstypeact", methods={"GET"})
     */
    public function stats () :Response{
        $typeActivites = $this->getDoctrine()
            ->getRepository(TypeActivite::class)
            ->findAll();

        $typeNom = [];
        $typeColor = [];
        $typeCount = [];

        foreach ($typeActivites as $typeActivite) {
            $typeNom []= $typeActivite->gettype();
            $typeColor []= $typeActivite->getcolor();
            $typeCount[]= count($typeActivite->getActivites());

        }

        return $this->render('type_activite/stats.html.twig',[
            'TypeNom' =>json_encode($typeNom),
            'TypeColor' =>json_encode($typeColor),
            'TypeCount' =>json_encode($typeCount)
        ]);
    }

    /**
     * @Route("statstyperec", name="statstyperec", methods={"GET"})
     */
    public function stats2 () :Response{
        $typeReclamations = $this->getDoctrine()
            ->getRepository(TypeReclamation::class)
            ->findAll();

        $typeNom = [];
        $typeColor = [];
        $typeCount = [];

        foreach ($typeReclamations as $typeReclamation) {
            $typeNom []= $typeReclamation->gettype();
            $typeColor []= $typeReclamation->getcolor();
            $typeCount[]= count($typeReclamation->getReclamations());

        }

        return $this->render('type_reclamation/stats.html.twig',[
            'TypeNom' =>json_encode($typeNom),
            'TypeColor' =>json_encode($typeColor),
            'TypeCount' =>json_encode($typeCount)
        ]);
    }

    /**
     * @Route("statsetatrec", name="statsetatrec", methods={"GET"})
     */
    public function stats3 () :Response{
        $etatReclamations = $this->getDoctrine()
            ->getRepository(Etatreclamation::class)
            ->findAll();

        $etatNom = [];
        $etatColor = [];
        $etatCount = [];

        foreach ($etatReclamations as $etatReclamation) {
            $etatNom []= $etatReclamation->getEtat();
            $etatColor []= $etatReclamation->getcolor();
            $etatCount[]= count($etatReclamation->getReclamations());

        }

        return $this->render('etatreclamation/stats.html.twig',[
            'TypeNom' =>json_encode($etatNom),
            'TypeColor' =>json_encode($etatColor),
            'TypeCount' =>json_encode($etatCount)
        ]);
    }

    /**
     * @Route("teststatsetatrec", name="teststatsetatrec", methods={"GET"})
     */
    public function teststats3 () :Response{
        $etatReclamations = $this->getDoctrine()
            ->getRepository(Etatreclamation::class)
            ->findAll();


        $data = [];
        $pieChart = new PieChart();
        foreach ($etatReclamations as $etatReclamation) {
            $etatNom = $etatReclamation->getEtat();
            $etatCount= (int)count($etatReclamation->getReclamations());

            array_push($data , [$etatNom,$etatCount]);

        }

        $pieChart->getData()->setArrayToDataTable(
            $data,true
        );

        $pieChart->getOptions()->setTitle('nombre de reclamation par etat');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('etatreclamation/teststats.html.twig',array('piechart' => $pieChart));
    }

    /**
     * @Route("teststatstyperec", name="teststatstyperec", methods={"GET"})
     */
    public function teststats2 () :Response{
        $typeReclamations = $this->getDoctrine()
            ->getRepository(TypeReclamation::class)
            ->findAll();

        $data = [];
        $pieChart = new PieChart();

        foreach ($typeReclamations as $typeReclamation) {
            $typeNom = $typeReclamation->gettype();
            $typeCount= (int)count($typeReclamation->getReclamations());
            array_push($data , [$typeNom,$typeCount]);

        }

        $pieChart->getData()->setArrayToDataTable(
            $data,true
        );

        $pieChart->getOptions()->setTitle('nombre de reclamation par type');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('type_reclamation/teststats.html.twig',array('piechart' => $pieChart));
    }

    /**
     * @Route("teststatstypeact", name="teststatstypeact", methods={"GET"})
     */
    public function teststats () :Response{
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
