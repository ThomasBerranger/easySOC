<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Service\SlackManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminUserController extends AbstractController
{
    private $em;
    private $slackManager;

    public function __construct(EntityManagerInterface $em, SlackManager $slackManager)
    {
        $this->em = $em;
        $this->slackManager = $slackManager;
    }

    /**
     * @Route("/admin/user/", name="user.list")
     */
    public function index() {

        $users = $this->em->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="user.edit")
     */
    public function edit(Request $request, User $user, $_route)
    {
        $form = $this->createForm(UserType::class, $user, array('route' => $_route));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form->getConfig()->getData()->setPlainPassword(''); // Evite l'erreur plainPassword blank

            $this->em->persist($user);
            $this->em->flush();

            $this->slackManager->sendMessage($this->getUser()->getUsername().' vient de modifier le compte de '.$user->getId().' (id) '.$user->getUsername().' (username).');

            return $this->redirectToRoute('user.list');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="user.delete")
     */
    public function delete(User $user, Request $request)
    {
            $this->em->remove($user);
            $this->em->flush();

        return $this->redirectToRoute('user.list');

    }

}