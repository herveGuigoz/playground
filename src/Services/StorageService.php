<?php

namespace App\Services;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StorageService
{
    private S3Client $client;

    private string $bucket = S3_BUCKET_NAME;

    public function __construct()
    {
        $this->client = new S3Client([
            'version'       => 'latest',
            'region'        => S3_REGION,
            'endpoint'      => S3_URL,
            'credentials'   => [
                'key'    => S3_ACCESS_KEY,
                'secret' => S3_ACCESS_SECRET,
            ],
            'use_path_style_endpoint' => true,
        ]);
    }

    public function read(string $key): ?string
    {
        $command = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);

        return $this->client->execute($command)->get('Body');
    }

    public function write(string $key, string $content): void
    {
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'Body' => $content,
            'ACL' => 'private',
        ]);
    }

    public function upload(string $key, UploadedFile $file): void
    {
        $this->client->upload(
            $this->bucket,
            $key,
            $file->getContent(),
            'private',
            [
                'params' => [
                    'ACL' => 'private',
                    'ContentType' => $file->getMimeType(),
                ]
            ]
        );
    }

    public function url(string $key, string $expiration = '+20 minutes'): string
    {
        $command = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);

        return $this->client->createPresignedRequest(
            $command,
            $expiration,
        )->getUri();
    }
}
