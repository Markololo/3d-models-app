<?php

namespace App\Controllers;

use App\Helpers\FileUploadHelper;
use App\Helpers\FlashMessage;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UploadController extends BaseController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * Display the upload form.
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $data = [
            'title' => 'File Upload Demo'
        ];

        return $this->render($response, 'upload/uploadView.php', $data);
    }

    /**
     * Process file upload.
     */
    public function upload(Request $request, Response $response, array $args): Response
    {
        // Get the uploaded file from the request.
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['myfile'];

        // Configure upload settings.
        $config = [
            'directory' => __DIR__ . '/../../public/uploads/images',
            'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif'],
            'maxSize' => 2 * 1024 * 1024, // 2MB in bytes
            'filenamePrefix' => 'upload_'
        ];

        // Use the helper to handle the upload.
        $result = FileUploadHelper::upload($uploadedFile, $config);

        // Handle the result.
        if ($result->isSuccess()) {
            // Get the filename from the result data.
            $filename = $result->getData()['filename'];

            // Store filename in session for display.
            if (!isset($_SESSION['uploaded_files'])) {
                $_SESSION['uploaded_files'] = [];
            }
            $_SESSION['uploaded_files'][] = $filename;

            // Show success message.
            FlashMessage::success($result->getMessage() . ": {$filename}");
        } else {
            // Show error message.
            FlashMessage::error($result->getMessage());
        }

        echo "HIIIIII";
        // Redirect back to the upload form using BaseController method.
        return $this->redirect($request, $response, 'upload.index');
    }
}
