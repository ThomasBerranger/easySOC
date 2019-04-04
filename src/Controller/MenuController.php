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
        return $this->render('menu/home.html.twig');
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
