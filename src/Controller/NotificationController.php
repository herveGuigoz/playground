<?php

namespace App\Controller;

use App\Services\FirebaseService;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends AbstractController
{
    private FirebaseService $firebase;

    public function __construct()
    {
        parent::__construct();
        $this->firebase = new FirebaseService();
    }

    public function index(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = array_map('trim', $_POST);

            $this->firebase->sendPushNotification(topic: $item['topic'], device: $item['device'], data: [
                'title' => 'Notification',
                'body' => 'This is the body',
                'redirect' => $item['redirect'],
            ]);

            return $this->render('Notifications/success.html.twig');
        }

        return $this->render('Notifications/index.html.twig');
    }

    public function success(): string
    {
        return $this->render('Notifications/success.html.twig');
    }
}
