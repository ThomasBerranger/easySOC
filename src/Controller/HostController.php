<?php

namespace App\Controller;

use App\Entity\Host;
use App\Form\HostType;
use App\Service\SlackManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HostController extends AbstractController
{
    private $em;
    private $slackManager;

    public function __construct(EntityManagerInterface $em, SlackManager $slackManager)
    {
        $this->em = $em;
        $this->slackManager = $slackManager;
    }

    /**
     * @Route("/host", name="host.list")
     */
    public function index(Request $request, $_route)
    {
        $hosts = $this->em->getRepository(Host::class)->findAll();
        $host = new Host();
        $form = $this->createForm(HostType::class, $host, array('route' => $_route));
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($host);
            $this->em->flush();

            return $this->redirectToRoute('host.list');
        }

        return $this->render('host/index.html.twig', [
            'form' => $form->createView(),
            'hosts' => $hosts
        ]);
    }

    

    /**
     * @Route("/host/edit/{id}", name="host.edit")
     */
    public function edit(Request $request, Host $host, $_route)
    {
        $form = $this->createForm(HostType::class, $host, array('route' => $_route));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($host);
            $this->em->flush();

            return $this->redirectToRoute('host.list');
        }

        return $this->render('host/edit.html.twig', [
            'form' => $form->createView(),
            'host' => $host,
        ]);
    }

    /**
     * @Route("/host/delete/{id}", name="host.delete")
     */
    public function delete(Host $host, Request $request)
    {
            $this->em->remove($host);
            $this->em->flush();

        return $this->redirectToRoute('host.list');

    }
}
