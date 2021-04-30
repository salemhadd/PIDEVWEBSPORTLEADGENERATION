<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use ContainerGRDt0MU\PaginatorInterface_82dac15;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class ExperienceController extends AbstractController
{
    /**
     * @Route("/tableexperience", name="experience_index", methods={"GET"})
     */
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $donnees = $this->getDoctrine()
            ->getRepository(Experience::class)
            ->findAll();
        $experience= $paginator->paginate(
            $donnees, // on passe les données
            $request->query->getInt('page',1),4 // Numero de la page en cours, 1 par defaut

        );



        return $this->render('experience/index.html.twig', [
            'experience' => $experience,
        ]);
    }






    /**
     * @ISGranted("ROLE_USER")
     * @Route("/ajouterexperience", name="experience_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($experience);
            $entityManager->flush();

            return $this->redirectToRoute('experience_new');
        }

        return $this->render('experience/new.html.twig', [
            'experience' => $experience,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/afficheexperience/{id}", name="experience_show", methods={"GET"})
     */
    public function show(Experience $experience): Response
    {
        return $this->render('experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    /**
     * @Route("/{id}/editexperience", name="experience_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Experience  $experience): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('experience_index');
        }

        return $this->render('experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("deleteexperience/{id}", name="experience_delete", methods={"POST"})
     */
    public function delete(Request $request, Experience $experience): Response
    {
        if ($this->isCsrfTokenValid('delete'.$experience->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($experience);
            $entityManager->flush();
        }

        return $this->redirectToRoute('experience_index');
    }


    /**
     * @Route("/imprimerexperience", name="imprimerexperience", methods={"GET"})
     */
    public function imprimer(): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $experience = $this->getDoctrine()
            ->getRepository(Experience::class)
            ->findAll();



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('experience/imprimer.html.twig', [
            'experience' => $experience,
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
     * @Route("/tableexperienceutilisateur", name="experience_indexfront", methods={"GET"})
     */
    public function affichelisteexperiencefront(Request $request,PaginatorInterface $paginator): Response
    {
        $donnees = $this->getDoctrine()
            ->getRepository(Experience::class)
            ->findAll();
        $experience= $paginator->paginate(
            $donnees, // on passe les données
            $request->query->getInt('page',1),4 // Numero de la page en cours, 1 par defaut

        );



        return $this->render('experience/indexfront.html.twig', [
            'experience' => $experience,
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

            $query =$this->getDoctrine()->getRepository(Experience::class)->createQueryBuilder('u');
            $query->where('u.titre LIKE :title')
                ->setParameter("title","%titre%")
                ->getQuery();


            $Experience = $query->getQuery()->getResult();

        }
        else
        {
            $type = $this->getDoctrine()
                ->getRepository(Experience::class)
                ->findAll();
        }


        return $this->json(['Experience' => $Experience]);
    }



}
