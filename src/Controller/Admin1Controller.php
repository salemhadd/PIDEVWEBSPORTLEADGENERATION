<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\EditUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

/**
 * @Route("/admin1", name="admin1_")
 */
class Admin1Controller extends AbstractController
{
    /**
     * @Route("/adminvalid", name="accueil")
     */
    public function index()
    {
        return $this->render('admin1/index.html.twig');
    }

    /**
     * @Route("/utilisateurs", name="utilisateurs")
     */
    public function usersList(UserRepository $user)
    {
        return $this->render('admin1/users.html.twig', [
            'user' => $user->findAll(),
        ]);
    }

    /**
     * @Route("/utilisateurs/modifier/{id}", name="modifier_utilisateur")
     */
    public function editUser($id ,Request $request,UserRepository $repository)
    {
        $user=$repository->find($id);
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('accueil');
        }

        return $this->render('admin1/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }



}
