<?php

namespace App\Services;

use Exception;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Message;
use Kreait\Firebase\Messaging\RawMessageFromArray;

class FirebaseService
{
    private Factory $firebase;

    public function __construct()
    {
        $storage = new StorageService();

        $this->firebase = (new Factory())
            ->withServiceAccount($storage->read('fcm.json'));
    }

    public function sendPushNotification(string $topic = '', string $device = '', array $data = []): void
    {
        if ($topic && $device) {
            throw new Exception('You must provide a topic or a device, not both');
        }

        if (!$topic && !$device) {
            throw new Exception('You must provide a topic or a device');
        }

        $title = $data['title'] ?? 'Notification';
        $body = $data['body'] ?? 'This is the body';

        $message = [
            // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#notification
            'notification' => ['title' => $title , 'body' => $body],
            'data' => $data,
            // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#androidconfig
            'android' => [
                'ttl' => '3600s',
                'priority' => 'high',
                'notification' => ['title' => $title , 'body' => $body],
            ],
            // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#apnsconfig
            'apns' => [
                'headers' => [
                    'apns-priority' => '10',
                ],
                'payload' => [
                    'aps' => [
                        'alert' => ['title' => $title , 'body' => $body],
                        'badge' => 42,
                    ],
                ],
            ],
            // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#fcmoptions
            'fcm_options' => [
                'analytics_label' => $topic
            ]
        ];

        if ($topic) {
            $message['topic'] = $topic;
        }

        if ($device) {
            $message['token'] = $device;
        }

        $this->send(new RawMessageFromArray($message));
    }

    private function send(Message $message): void
    {
        $messaging = $this->firebase->createMessaging();
        $messaging->send($message);
    }
}
