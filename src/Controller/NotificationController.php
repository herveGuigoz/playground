<?php

namespace App\Controller;

use App\Services\FirebaseService;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends AbstractController
{
    public function index(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firebase = new FirebaseService();

            $items = array_map('trim', $_POST);

            $firebase->sendPushNotification(topic: $items['topic'], device: $items['device'], data: [
                'title' => 'Notification',
                'body' => 'This is the body',
                'redirect' => $items['redirect'],
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
