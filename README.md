# PHP Playground

## Description

Experimental project to test Oauth, Push Notifications and other PHP features.

## Installation

This project is ready to use with docker.

To build the image, go into the project directory and in your CLI type:

```
docker compose build --pull --no-cache
```

then, run it to open it on your localhost :

```
docker compose up --detach
```

### Populate the database

```
docker compose exec php php migration.php
```

## Authentication

### Google API

#### Configure a Google API Console project

1. Open an existing project in the [API Console](https://console.cloud.google.com/), or create a new project.
2. On the OAuth consent screen page, make sure all of the information is complete and accurate.
3. On the Credentials page, create an Android type client ID for your app if you don't already have one. You will need
to specify your app's package name and SHA-1 certificate fingerprint.
See [Authenticating Your Client](https://developers.google.com/android/guides/client-auth) for more information.

#### Get your backend server's OAuth 2.0 client ID
You need to get the OAuth 2.0 client ID that represents your backend server.

On the Credentials page, create a Web application type client ID. Take note of the client ID string, which you will need
to pass to the requestIdToken or requestServerAuthCode method when you create the GoogleSignInOptions object.

Add your `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` and `GOOGLE_REDIRECT_URI` in the .env file.

### Facebook API

[Documentation](https://developers.facebook.com/apps/)

### Apple API

Todo

### Push Notifications

To use Push Notifications, you need to create a project on [Firebase](https://console.firebase.google.com/)

Then generate FCM server credentials from the Firebase console:
- Go to the project settings
- Navigate to the Cloud Messaging tab
- Generate and download the json file containing the credentials.
- Add the file to the project root directory and rename it `fcm.json`.
