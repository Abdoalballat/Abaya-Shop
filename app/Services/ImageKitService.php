<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Exception;

class ImageKitService
{
    protected string $privateKey;
    protected string $urlEndpoint;
    protected string $uploadApiUrl = 'https://upload.imagekit.io/api/v1/files/upload';
    protected string $deleteApiUrl = 'https://api.imagekit.io/v1/files/';

    public function __construct()
    {
        $this->privateKey = config('services.imagekit.private_key') ?? '';
        $this->urlEndpoint = rtrim(config('services.imagekit.url_endpoint') ?? '', '/');
    }

    public function upload(UploadedFile $file, string $folder = 'products'): object
    {
        $response = Http::withBasicAuth($this->privateKey, '')
            ->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )
            ->post($this->uploadApiUrl, [
                'fileName'          => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . uniqid(),
                'folder'            => '/' . trim($folder, '/'),
                'useUniqueFileName' => 'true',
            ]);

        if ($response->failed()) {
            throw new Exception($response->json('message') ?? 'فشل رفع الصورة إلى خوادم ImageKit');
        }

        $data = $response->json();

        return (object) [
            'url'    => $data['url'],
            'fileId' => $data['fileId'],
        ];
    }

    public function delete(?string $fileId): bool
    {
        if (!$fileId) {
            return false;
        }

        $response = Http::withBasicAuth($this->privateKey, '')
            ->delete($this->deleteApiUrl . $fileId);

        return $response->successful();
    }
}