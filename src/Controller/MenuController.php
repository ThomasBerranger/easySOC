<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Service\MenuService;
use App\Service\SlackManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    private $slackManager;
    private $menuService;

    public function __construct(SlackManager $slackManager, MenuService $menuService)
    {
        $this->slackManager = $slackManager;
        $this->menuService = $menuService;
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $generalAttackHistory = $this->menuService->getGeneralAttackHistory();

        $generalLogHistory = $this->menuService->getGeneralLogHistory();

        return $this->render('menu/home.html.twig', array(
            'generalAttackHistory' => $generalAttackHistory,
            'generalLogHistory' => $generalLogHistory,
        ));
    }

    /**
     * @Route("/alert", name="alert")
     */
    public function alert()
    {
        $alerts = $this->getDoctrine()->getRepository(Alert::class)->findBy([], ['created_at' => 'DESC']);

//        dump($alerts);die;

        return $this->render('menu/alert.html.twig', array(
            'alerts' => $alerts,
        ));
    }

    /**
     * @Route("/scan", name="scan")
     */
    public function scan()
    {
        return $this->render('menu/scan.html.twig');
    }

    /**
     * @Route("/support", name="support")
     */
    public function support()
    {
        if ($_POST && $_POST['order'] && $_POST['content']) {
            $this->slackManager->sendMessage(
                'Message de '.$this->getUser()->getUsername().' ('.$this->getUser()->getEmail().').'
            );
            $this->slackManager->sendMessage(
                'Ordre : '.$_POST['order'].'.'
            );
            $this->slackManager->sendMessage(
                'Message : '.$_POST['content']
            );

            $this->addFlash('success', 'Message successfully send.');
        } elseif ($_POST && !$_POST['order']) {
            $this->addFlash('error', 'Please write an order.');
        } elseif ($_POST && !$_POST['content']) {
            $this->addFlash('error', 'Please describe your problem.');
        }

        return $this->render('menu/support.html.twig');
    }
}
