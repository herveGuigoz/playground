<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new DebugExtension());
    }

    protected function render(string $template, array $parameters = []): Response
    {
        $content = $this->twig->render($template, $parameters);
        $response = new Response();
        $response->setContent($content);

        return $response;
    }

    protected function redirect(string $url, int $status = 302): Response
    {
        return new RedirectResponse($url, $status);
    }

    protected function json(mixed $data, int $status = 200, array $headers = []): Response
    {
        return new JsonResponse($data, $status, $headers);
    }
}
