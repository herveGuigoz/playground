<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('Home/index.html.twig');
    }
}
