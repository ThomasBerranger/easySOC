<?php

namespace App\Controller;

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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SecurityController extends AbstractController
{
    private $slackManager;

    public function __construct(SlackManager $slackManager)
    {
        $this->slackManager = $slackManager;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/admin/user/create", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, $_route)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, array('route' => $_route));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setUsername(strtolower($user->getFirstname().'.'.$user->getLastname()));
            $this->getUser()->getCompany()? $user->setCompany($this->getUser()->getCompany()) : $user->setCompany(null);

            $this->em->persist($user);
            $this->em->flush();

            $this->slackManager->sendMessage($this->getUser()->getUsername().' vient de créer le compte : '.$user->getId().' (id) '.$user->getUsername().' (username).');

            return $this->redirectToRoute('home');
        }

        return $this->render('Security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit", name="security.edit", methods="GET|POST")
     */
    public function edit(Request $request, $_route)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user, array('route' => $_route));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form->getConfig()->getData()->setPlainPassword(''); // Evite l'erreur plainPassword blank
            $user->setUsername(strtolower($user->getFirstname().'.'.$user->getLastname()));

            $this->em->persist($user);
            $this->em->flush();

            $this->slackManager->sendMessage($this->getUser()->getId().' (id) '.$this->getUser()->getUsername().' (username) vient de modifier son compte.');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
