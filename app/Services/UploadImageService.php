<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;

class UploadImageService
{
  public function upload(UploadedFile $file, string $folder = 'products'): string
  {
    $filename = uniqid('img_') . '.' . $file->getClientOriginalExtension();
    return $file->storeAs($folder, $filename, 'public');
  }
}