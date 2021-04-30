<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Materiel;
use App\Form\MaterielType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/Materiel")
 */

class MaterielController extends AbstractController
{
    /**
     * @Route("/home", name="materiel_index", methods={"GET"} )
     */
    public function index(): Response
    {
        $materiels = $this->getDoctrine()
            ->getRepository(Materiel::class)
            ->findAll();

        return $this->render('materiel/index.html.twig', [
            'materiels' => $materiels,
        ]);
    }
    /**
     * @Route("/imprimer", name="materiel_imprimer", methods={"GET"})
     */
    public function imprimer(): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $materiel = $this->getDoctrine()
            ->getRepository(materiel::class)
            ->findAll();



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('materiel/imprimer.html.twig', [
            'materiel' => $materiel,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);


    }

    /**
     * @Route("/new", name="materiel_new", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy): Response
    {
        $materiel = new Materiel();

        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $materiel->getImage();
            $fileName =md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$fileName);
            $materiel->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();
            $flashy->success('Materiel Ajouter', 'http://your-awesome-link.com');
            return $this->redirectToRoute('materiel_index');
        }

        return $this->render('materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materiel_show", methods={"GET"},requirements={"id":"\d+"})
     */
    public function show(Materiel $materiel): Response
    {
        return $this->render('materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="materiel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Materiel $materiel): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $materiel->getImage();
            $fileName =md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$fileName);
            $materiel->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('materiel_index');
        }

        return $this->render('materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materiel_delete", methods={"POST"})
     */
    public function delete(Request $request, Materiel $materiel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($materiel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('materiel_index');
    }
    /**
     * @Route("/homeFront", name="materielFront_index", methods={"GET"} )
     */
    public function index_front(): Response
    {
        $materiel = $this->getDoctrine()
            ->getRepository(Materiel::class)
            ->findAll();

        return $this->render('materiel/index_front.html.twig', [
            'materiel' => $materiel,
        ]);
    }
    /**
     * @Route("/Detail/{id}", name="DetailMateriel2", methods={"GET"},requirements={"id":"\d+"})
     */
    public function DetailMateriel(Materiel $materiel): Response
    {
        return $this->render('materiel/DetailMateriel.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    /**
     * @Route("/rate", name="rate", methods={"POST"})
     */
    public function rateAction( \Symfony\Component\HttpFoundation\Request $request){
        $data = $request->getContent();
        $obj = json_decode($data,true);

        $em = $this->getDoctrine()->getManager();
        $rate =$obj['rate'];
        $idc = $obj['materiel'];
        $materiel = $em->getRepository(Materiel::class)->find($idc);
        $note = ($materiel->getRate()*$materiel->getVote() + $rate)/($materiel->getVote()+1);
        $materiel->setVote($materiel->getVote()+1);
        $materiel->setRate($note);
        $em->persist($materiel);
        $em->flush();
        return new Response($materiel->getRate());
    }
    /**
     * @Route("/updateRate/{id}", name="updateRate")
     */
    public function updateRate($id, Request $request)
    {
        $materiel = $this->getDoctrine()
            ->getRepository(Materiel::class)
            ->findAll();
        //préparation de l'objet
        // $objet = $em->getRepository(Article::class)->find($id_article);

        $servername = "localhost";//Server Name
        $username = "root";//Server User Name
        $password = "";//Server Password
        $dbname = "pijava";//Database Name
        ////Create DB Connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $rating = $_POST["rating"];

            $sql = "UPDATE  materiel set rate='$rating' where id= '$id' ";

            if (mysqli_query($conn, $sql)) {
                echo "-";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
        return $this->render('materiel/index_front.html.twig',[
            'materiel' => $materiel,
        ]);
    }


//
    //  /**
    //  * @Route("/statistiques",name="statistiques")
    //  */
    //  public function statistiques(): Response
    // {
    //  $r=$this->getDoctrine()->getRepository(Etat::class);
    // $nbs = $r->getNb();
    //   $p = $this->getDoctrine()->getRepository(Materiel::class);



    //  $data = [['Materiel', 'Nombre de Materiel']];

    //  foreach($nbs as $nb)
    //  {
    // $materiel=$p->findOneBy(['id_materiel'=>$nb['materile']]);
    // $nom=$materiel->getNom();
    //   $id = $materiel ->getId();

    //  $etat = $this->getDoctrine()->getRepository(Etat::class)->findOneBy(['id'=>$id]);
    // $typeEtat= $etat->getTypeEtat();

    // $aux= $nom.":".$typeEtat;



    // $data[] = array(
    //    $aux, $nb['res'])
    //  ;
    // }/
    // $bar = new BarChart();
    //  $bar->getData()->setArrayToDataTable(
    //      $data
    //  );
    //  $bar->getOptions()->setTitle('Nombre de materiel');
    // $bar->getOptions()->getTitleTextStyle()->setColor('#07600');
    // $bar->getOptions()->getTitleTextStyle()->setFontSize(25);
    //  return $this->render('Back/Statistiques/statistiques.html.twig', array('barchart' => $bar,'nbs' => $nbs));

    // }

}