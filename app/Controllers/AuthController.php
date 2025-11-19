<?php

namespace App\Controllers;

use App\Domain\Models\UserModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function __construct(Container $container, private UserModel $userModel)
    {
        parent::__construct($container);
    }

    /**
     * Display the registration form (GET request).
     */
    public function register(Request $request, Response $response, array $args): Response
    {
        // TODO: Create a $data array with 'title' => 'Register'

        // TODO: Render 'auth/register.php' view and pass $data
        return $response;
    }

    /**
     * Process registration form submission (POST request).
     */
    public function store(Request $request, Response $response, array $args): Response
    {
        // TODO: Get form data using getParsedBody()
        //       Store in $formData variable

        // TODO: Extract individual fields from $formData:
        //       $firstName, $lastName, $username, $email, $password, $confirmPassword, $role

        // Start validation
        $errors = [];

        // TODO: Validate required fields (first_name, last_name, username, email, password, confirm_password)
        //       If any field is empty, add error: "All fields are required."
        //       Hint: if (empty($firstName) || empty($lastName) || ...) { $errors[] = "..."; }

        // TODO: Validate email format using filter_var()
        //       If invalid, add error: "Invalid email format."
        //       Hint: if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { ... }

        // TODO: Check if email already exists using $this->userModel->emailExists($email)
        //       If exists, add error: "Email already registered."

        // TODO: Check if username already exists using $this->userModel->usernameExists($username)
        //       If exists, add error: "Username already taken."

        // TODO: Validate password length (minimum 8 characters)
        //       If too short, add error: "Password must be at least 8 characters long."

        // TODO: Validate password contains at least one number
        //       If no number, add error: "Password must contain at least one number."
        //       Hint: if (!preg_match('/[0-9]/', $password)) { ... }

        // TODO: Check if password matches confirm_password
        //       If not match, add error: "Passwords do not match."

        // If validation errors exist, redirect back with error message
        // TODO: Check if $errors array is not empty
        //       If errors exist:
        //         - Use FlashMessage::error() with the first error message
        //         - Redirect back to 'auth.register' route

        // If validation passes, create the user
        try {
            // TODO: Create $userData array with keys:
            //       'first_name', 'last_name', 'username', 'email', 'password', 'role'

            // TODO: Call $this->userModel->createUser($userData)
            //       Store the returned user ID in $userId

            // TODO: Display success message using FlashMessage::success()
            //       Message: "Registration successful! Please log in."

            // TODO: Redirect to 'auth.login' route

        } catch (\Exception $e) {
            // TODO: Display error message using FlashMessage::error()
            //       Message: "Registration failed. Please try again."

            // TODO: Redirect back to 'auth.register' route
        }
        return $response;

    }
}
