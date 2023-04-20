<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class PolicyController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('Policy/index.html.twig');
    }
}
