<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\Result;
use App\Service\AlertService;
use App\Service\SlackManager;
use DateTime;
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
        key_exists('since', $_POST)? $since = $_POST['since'] : $since = date('Y');
        key_exists('to', $_POST) && $_POST['since'] != $_POST['to']? $to = $_POST['to'] : $to = null;

        $alerts = $this->getDoctrine()->getRepository(Alert::class)->findBy([], ['created_at' => 'desc']);

        $formattedAlertsData = $this->alertService->getFormattedAlerts($alerts, $since, $to);

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
        $resultToReturn = [];
        $postedData = [];

        if (!empty($_POST) && !$_POST['ipAddress']) {
            $this->addFlash('error', 'Please, specify an IP address');
        } elseif (!empty($_POST) && !key_exists('module', $_POST)) {
            $this->addFlash('error', 'Please, specify at least one module');
        }
        elseif (!empty($_POST)) {
            $postedData = $_POST;
            $results = $this->getDoctrine()->getRepository(Result::class)->findBy(['ip' => $_POST['ipAddress']]);

            /** @var Result $result */
            foreach ($results as $result) {
                foreach ($_POST['module'] as $key => $module) {
                    if ($result->getModule()->getId() == $key) {
                        array_push($resultToReturn, $result);
                    }
                }
            }

        }

        return $this->render('Menu/scan.html.twig', array(
            'results' => $resultToReturn,
            'postedData' => $postedData,
        ));
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
