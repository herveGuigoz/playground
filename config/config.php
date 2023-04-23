<?php

define('APP_ENV', getenv('APP_ENV') ?: 'dev');

// Database
define('DATABASE_URL', getenv('DATABASE_URL'));

// Firebase
const FIREBASE_CREDENTIALS = __DIR__ . '/../fcm.json';

// Storage
define('S3_URL', getenv('S3_URL'));
define('S3_REGION', getenv('S3_REGION'));
define('S3_BUCKET_NAME', getenv('S3_BUCKET_NAME'));
define('S3_ACCESS_KEY', getenv('S3_ACCESS_KEY'));
define('S3_ACCESS_SECRET', getenv('S3_ACCESS_SECRET'));

// Oauth
define('OAUTH_REDIRECT_URI', getenv('OAUTH_REDIRECT_URI'));
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI'));
define('APPLE_CLIENT_ID', getenv('APPLE_CLIENT_ID'));
define('APPLE_TEAM_ID', getenv('APPLE_TEAM_ID'));
define('APPLE_KEY_ID', getenv('APPLE_KEY_ID'));
define('APPLE_SERVICE_ID', getenv('APPLE_SERVICE_ID'));

define('FACEBOOK_CLIENT_ID', getenv('FACEBOOK_CLIENT_ID'));
define('FACEBOOK_CLIENT_SECRET', getenv('FACEBOOK_CLIENT_SECRET'));
define('FACEBOOK_REDIRECT_URI', getenv('FACEBOOK_REDIRECT_URI'));

// View
const APP_VIEW_PATH = __DIR__ . '/../src/View/';

// database dump file path for automatic import
const DB_DUMP_PATH = __DIR__ . '/../database.sql';
