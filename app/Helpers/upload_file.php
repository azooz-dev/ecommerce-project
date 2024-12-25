<?php

namespace App\Helpers;

use Exception;


if (!function_exists('uploadFile')) {
  function uploadFile($file, $path, $disk = 'public')
  {
    try {
      $fileName = time() . '_' . $file->getClientOriginalName();
      $file->storeAs($path, $fileName, $disk);

      return $fileName;
    } catch (Exception $e) {
      return errorResponse($e->getMessage(), 500);
    }
  }
}
