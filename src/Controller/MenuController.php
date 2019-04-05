<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Service\AlertService;
use App\Service\SlackManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    private $slackManager;
    private $alertService;

    public function __construct(SlackManager $slackManager, AlertService $alertService)
    {
        $this->slackManager = $slackManager;
        $this->alertService = $alertService;
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $generalAttackHistory = $this->alertService->getGeneralAttackHistory();

        $generalLogHistory = $this->alertService->getGeneralLogHistory();

        return $this->render('Menu/home.html.twig', array(
            'chartData' => $generalAttackHistory,
            'generalLogHistory' => $generalLogHistory,
        ));
    }

    /**
     * @Route("/alert", name="alert")
     */
    public function alert()
    {
        $formattedAlertsData = $this->alertService->getFormattedAlerts();
        $alerts = $this->getDoctrine()->getRepository(Alert::class)->findBy([], ['created_at' => 'desc']);

        return $this->render('Menu/alert.html.twig', array(
            'alerts' => $alerts,
            'chartData' => $formattedAlertsData,
        ));
    }

    /**
     * @Route("/scan", name="scan")
     */
    public function scan()
    {
        return $this->render('Menu/scan.html.twig');
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

        return $this->render('Menu/support.html.twig');
    }
}
