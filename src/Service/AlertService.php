<?php

namespace App\Service;

use App\Entity\Alert;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class AlertService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGeneralAttackHistory()
    {
        $attacks = [
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

        return $attacks;
    }

    public function getGeneralLogHistory()
    {
        $alerts = $this->em->getRepository(Alert::class)->findBy([], ['created_at' => 'DESC']);

        return $alerts;
    }

    public function getFormattedAlerts($alerts, $since, $to)
    {
        $alertsByMonth = $this->formatAlerts($alerts, $since, $to);

        return $alertsByMonth;
    }

    private function formatAlerts($alerts, $since, $to)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $alertsByMonth = [];
        $alertsByMonth2 = [];

        foreach ($months as $month) {
            $alertsByMonth[$month] = 0;
            $alertsByMonth2[$month] = 0;
        }

        /** @var Alert $alert */
        foreach ($alerts as $alert) {
            foreach ($months as $key => $month) {
                if (new DateTime('first day of '.$month.' '.$since) <= $alert->getCreatedAt() && $alert->getCreatedAt() <= new DateTime('last day of '.$month.' '.$since.' 23:59:59.59')) {
                    $alertsByMonth[$month] = $alertsByMonth[$month] + 1;
                    break;
                }
            }
        }

        $formattedAlertsData = [
            'id' => 'alerts-chart',
            'type' => 'line',
            'labels' => $months,
            'dataSets' => [
                [
                    'data' => $alertsByMonth,
                    'label' => $since,
                    'borderColor' => "#E8414A",
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

        if ($to) {
            /** @var Alert $alert */
            foreach ($alerts as $alert) {
                foreach ($months as $key => $month) {
                    if (new DateTime('first day of '.$month.' '.$to) <= $alert->getCreatedAt() && $alert->getCreatedAt() <= new DateTime('last day of '.$month.' '.$to.' 23:59:59.59')) {
                        $alertsByMonth2[$month] = $alertsByMonth2[$month] + 1;
                        break;
                    }
                }
            }
            array_push($formattedAlertsData['dataSets'], [
                'data' => $alertsByMonth2,
                'label' => $to,
                'borderColor' => "#3e95cd",
                'fill' => 0
            ]);
        }

        return $formattedAlertsData;
    }
}