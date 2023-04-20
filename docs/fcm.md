# Sending push notifications with Firebase cloud message (FCM)

Before digging into details it's important to understand the architectural idea
![image](https://user-images.githubusercontent.com/1279756/41772490-2402772a-7619-11e8-9acf-f0c17cbd0b75.png)

1. Using the FCM-SDK, the app will request a unique push-token for the device, which will expire after a certain number of days.
2. The app also provides the option to subscribe or unsubscribe to topics. Topics can be used to send push notifications
to a group of devices, or to register a user ID as a topic, thereby avoiding the need to store push settings in the database.
3. To send a push notification from a backend project, the FCM API is used. The backend can send a notification either
to a single device using its token, or to a topic.
4. When a notification is sent to a topic, FCM looks up the topic, loops through each push-token registered to this
channel, and sends the notification to each push-network, one at a time, asynchronously in a queue.
5. The push-network then queues the request from FCM and sends the push notification through a socket connection to the device.

## SDKs

- PHP: https://github.com/kreait/firebase-php

## Messages

A push message contains title, body, and payload. The title and body are displayed on the device, and the payload is
passed to the app.

***It's highly recommend using both title & body, but body is optional***

### Android Push Notification Structure

```php
$config = AndroidConfig::fromArray([
    'ttl' => '3600s',
    'priority' => 'normal',
    'notification' => [
        'title' => 'Title',
        'tag' => 'unique_notification_identifier',
        'body' => 'Body',
        'icon' => 'ic_launcher',
        'color' => '#f45342'
    ],
]);
```
*Notification keys and value options*:

`tag`: Identifier used to replace existing notifications in the notification drawer. If not specified, each request creates a new notification. If specified and a notification with the same tag is already being shown, the new notification replaces the existing one in the notification drawer.

`color`: The notification's icon color, expressed in #rrggbb format.

`icon`: Unless specified, let the Android app deal with this. Kind of similar to sound, but with notification icons instead.

### iOS Push Notification Structure

On iOS, push notifications are structured as binary payloads that are sent via the Apple Push Notification Service (APNS).
Here's an example of what an iOS push notification payload might look like:

```php
$config = ApnsConfig::fromArray([
    'headers' => [
        'apns-priority' => '10',
    ],
    'payload' => [
        'aps' => [
            'alert' => [
                'title' => 'Title',
                'body' => 'Body',
            ],
            'badge' => 42,
        ],
    ],
]);
```
*Notification keys and value options*:

`headers`: The header key in APNS configuration refers to the HTTP/2 headers such as apns-priority that specifies the
priority of the push notification. The apns-priority header field can have two possible values:
10 - The push notification is sent immediately and wakes up the device if it is in a suspended state (i.e. the app is
not currently running), 5 - The push notification is sent at a time that conserves power on the device.
This is the default priority if apns-priority is not included in the APNS configuration.

`payload`: It's includes an "aps" dictionary that contains information about how the notification should be displayed,
including the title, body text, sound, and badge number. In addition to the "aps" dictionary, the payload can also
include custom data that the app can use to handle the notification.

### Payload

The payload is used to include custom data such as notification type or deep-link to a specific page in the app.
It's a JSON object with a limit of 2kb.

```php
$message = $message->withData($[
    'redirect' => '/path/to/somewhere',
]);
```

FCM does not support nested data. If you need to send a full model, consider json-encoding it to 1 key.

### Topics

Topics can be used very smart, and should avoid every situation of having to store push settings in the DB, example:

- Generic topics: To receive notifications related to a specific category, the user can toggle their subscription to
that category within the app. Whenever a new notification is available for that category, the app's backend will send a
message to FCM, including the category identifier as the topic.

- Registered User: To send a notification to a specific user, the user's ID can be used as the topic. This way, the
backend does not need to store the user's token in the database.

- Specific Resource: The backend can also send a notification related to a specific resource. In this case, the app will
subscribe to a topic that is a combination of the resource type and the resource ID. For example, a notification related
to a specific alert with ID 123 would be sent to the topic "alert_123".

#### Conditional Topics

In Firebase Cloud Messaging, the backend has the ability to apply conditions to topics. This means that you
can specify certain criteria that must be met before a user receives a push notification.

For example, let's say a user has subscribed to specific alert, but only wants to receive notifications when the alert
is closed. In this case, the backend can apply a condition:

```php
$condition = "'alert_123' in topics && 'alert_closed' in topics";

$message = ConditionalMessage::create($condition)
    ->withNotification($notification)
    ->withData($data);
```

### Priority

When it comes to sending push notifications with FCM (Firebase Cloud Messaging), there are two priority levels to choose
from: normal and high. These priority levels correspond to the priority levels of 5 and 10 for APNs (Apple Push
Notification service) on iOS devices.

Normal priority notifications may experience a delay of several minutes before being sent to the device, while high
priority notifications are delivered more quickly. It's important to consider the urgency and importance of your push
notifications when choosing the priority level, as this can impact the user experience and engagement with your app.

