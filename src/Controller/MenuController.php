<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $chartData = [
            'id' => 'general-history',
            'type' => 'line',
            'labels' => ['02/05/18', '03/05/18', '04/05/18', '05/05/18', '06/05/18', '07/05/18', '08/05/18', '09/05/18', '10/05/18', '11/05/18'],
            'dataSets' => [
                [
                    'data' => [10, 20, 30, 50, 70, 60, 50, 40, 45, 50],
                    'label' => "DDOS",
                    'borderColor' => "#3e95cd",
                    'fill' => 0
                ],
                [
                    'data' => [20, 40, 30, 30, 35, 35, 45, 60, 70, 80],
                    'label' => "XSS",
                    'borderColor' => "#8e5ea2",
                    'fill' => 0
                ],
            ],
            'options' => [
                'title' => [
                    'display' => 0,
                    'text' => 'A title'
                ],
                'responsive' => 1,
                'legend' => [
                    'position' => 'bottom',
                ]
            ],
        ];

        return $this->render('menu/home.html.twig', array(
            'chartData' => $chartData,
        ));
    }

    /**
     * @Route("/alert", name="alert")
     */
    public function alert()
    {
        return $this->render('menu/alert.html.twig');
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
        return $this->render('menu/support.html.twig');
    }
}
