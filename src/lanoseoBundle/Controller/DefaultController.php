<?php

namespace lanoseoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('lanoseoBundle:Default:index.html.twig', array(

            "car_company" => "Mitsubishi Outlander",
            "car_description" => "bardzo długi opis z bazy... bardzo długi opis z bazy... bardzo długi opis z bazy... bardzo długi opis z bazy... bardzo długi opis z bazy... bardzo długi opis z bazy... bardzo długi opis z bazy...",
            "car_image_name" => "outlander",


        ));
    }
    public function contactAction()
    {
        return $this->render('lanoseoBundle:Default:contact.html.twig');
    }

    public function loginAction()
    {
        return $this->render('lanoseoBundle:Default:login.html.twig');
    }

    public function registerAction()
    {
        return $this->render('lanoseoBundle:Default:register.html.twig');
    }
    public function carlistAction()
    {
        return $this->render('lanoseoBundle:Default:carlist.html.twig');
    }


}
