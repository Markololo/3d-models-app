<?php

namespace App\Helpers;

use App\Helpers\Core\Result;
use Psr\Http\Message\UploadedFileInterface;

class FileUploadHelper
{
    /**
     * Upload a file with validation and return a Result.
     *
     * @param UploadedFileInterface $uploadedFile The uploaded file from the request
     * @param array $config Configuration options:
     *   - 'directory' (string): Upload directory path (required)
     *   - 'allowedTypes' (array): Array of allowed media types (required)
     *   - 'maxSize' (int): Maximum file size in bytes (required)
     *   - 'filenamePrefix' (string): Prefix for generated filenames (default: 'upload_')
     * @return Result Success with filename, or failure with error message
     */
    public static function upload(UploadedFileInterface $uploadedFile, array $config): Result
    {
        //? Extraction:
        $directory = $config['directory'] ?? null;
        $allowedTypes = $config['allowedTypes'] ?? [];
        $maxSize = $config['maxSize'] ?? 0;
        $filenamePrefix = $config['filenamePrefix'] ?? 'upload_';

        //? Validation:
        if(!$directory) {
            $result = Result::failure('Upload directory not specified in configuration');
            return $result;
        } elseif (empty($allowedTypes)) {
            return Result::failure('Allowed file types not specified in configuration');
        } elseif ($maxSize <= 0) {
            return Result::failure('Maximum file size not specified in configuration');
        }

        $errors = $uploadedFile->getError();
        if($errors != UPLOAD_ERR_OK) {
            return Result::failure('Error uploading file');
        }

        $size = $uploadedFile->getSize();
        if($size > $maxSize) {
            //calculate size in MBL:
            $maxSizeMB = round($maxSize / (1024 * 1024), 1);
            return Result::failure("File too large (max {$maxSizeMB}MB)");
        }

        $mediaType = $uploadedFile->getClientMediaType();
        if(!in_array($mediaType, $allowedTypes)) {
            return Result::failure('Invalid file type. Only ' . implode(', ', $allowedTypes) . ' allowed.');
        }

        //? Generate safe filename:
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $filename = uniqid($filenamePrefix) . '.' . $extension;

        //? Create Directory and :
        if(!is_dir($directory)) {
            $creationStatus = mkdir($directory, 0755, true);
            if(!$creationStatus) {
                return Result::failure('Failed to create upload directory');
            }
        }

        $destination = $directory . DIRECTORY_SEPARATOR . $filename;

        try {
            $uploadedFile->moveTo($destination);
        } catch (\Exception $e) {
            return Result::failure('Failed to save uploaded file: ' . $e->getMessage());
        }

        return Result::success('File uploaded successfully', ['filename' => $filename]);
    }
}
