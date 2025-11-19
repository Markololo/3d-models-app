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
        $data = [
            'page_title' => 'Registration Page',
        ];

        return $this->render($response, 'auth/register.php', $data);
    }

    /**
     * Process registration form submission (POST request).
     */
    public function store(Request $request, Response $response, array $args): Response
    {
        //? 1) Parse the request:
        $formData = $request->getParsedBody();
        $firstName = $formData["first_name"];
        $lastName = $formData["first_name"];
        $username = $formData["username"];
        $email = $formData["email"];
        $password = $formData["password"];
        $confirmPassword = $formData["confirmPassword"];
        $role = $formData["role"];

        //? 2) Start validation:
        $errors = [];

        if (empty($firstName) || empty($lastName) || empty($username)|| empty($email)|| empty($password)|| empty($confirmPassword)|| empty($role))
        {
            errors[] = "All fields are required!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
             $errors[] = "Invalid email format.";
        }

        $emailExists =  $this->userModel->emailExists($email);
        if($emailExists)
        {
            $errors = ["Email already registered."];
        }

        $usernameExists =  $this->userModel->usernameExists($username);
        if($usernameExists)
        {
            $errors = ["Username already taken."];
        }

        if(strlen($password) < 8)
        {
            $errors[] = "Password must be at least 8 characters long.";
        }

        if (!preg_match('/[0-9]/', $password))
        {
            $errors[] = "Password must contain at least one number.";
        }

        if($password !== $confirmPassword)
        {
            $errors[] = "Passwords do not match.";
        }

        // If validation errors exist, redirect back with error message
        if(empty($errors))
        {
            return $this->render($response, 'auth/register.php', $errors);
        }
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
