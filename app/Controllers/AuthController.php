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
        $lastName = $formData["last_name"];
        $username = $formData["username"];
        $email = $formData["email"];
        $password = $formData["password"];
        $confirmPassword = $formData["confirm_password"];
        $role = $formData["role"];

        //? 2) Start validation:
        $errors = [];

        if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
            $errors[] = "All fields are required!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // $emailExists =  $this->userModel->emailExists($email);
        if ($email && $this->userModel->emailExists($email)) {
            $errors[] = "Email already registered.";
        }

        $usernameExists =  $this->userModel->usernameExists($username);
        if ($usernameExists) {
            $errors[] = "Username already taken.";
        }

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        // If validation errors exist, redirect back with error message
        if (!empty($errors)) {
            //       If errors exist:
            //         - Use FlashMessage::error() with the first error message
            //         - Redirect back to 'auth.register' route

            FlashMessage::error($errors[0]);
            return $this->redirect($request, $response, 'auth.register');
        } else {
            try {
                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role
                ];

                $userId = $this->userModel->createUser($userData);



                FlashMessage::success("Registration successful! Please log in.");

                return $this->redirect($request, $response, 'auth.login');
            } catch (\Exception $e) {
                FlashMessage::error("Registration failed. Please try again.");

                return $this->redirect($request, $response, 'auth.register');
            }
        }
    }

    /**
     * Display the login form (GET request).
     */
    public function login(Request $request, Response $response, array $args): Response
    {
        // TODO: Create a $data array with 'title' => 'Login'

        // TODO: Render 'auth/login.php' view and pass $data
    }

    /**
     * Process login form submission (POST request).
     */
    public function authenticate(Request $request, Response $response, array $args): Response
    {
        // TODO: Get form data using getParsedBody()

        // TODO: Extract 'identifier' and 'password' from form data

        // Start validation
        $errors = [];

        // TODO: Validate required fields (identifier and password)
        //       If either is empty, add error: "Email/username and password are required."

        // If validation errors exist, redirect back
        // TODO: Check if $errors array is not empty
        //       If errors exist, use FlashMessage::error() and redirect to 'auth.login'

        // Attempt to verify user credentials
        // TODO: Call $this->userModel->verifyCredentials($identifier, $password)
        //       Store the result in $user variable

        // Check if authentication was successful
        // TODO: If $user is null (authentication failed):
        //       - Display error message: "Invalid credentials. Please try again."
        //       - Redirect back to 'auth.login'

        // Authentication successful - create session
        // TODO: Store user data in session using SessionManager:
        //       SessionManager::set('user_id', $user['id']);
        //       SessionManager::set('user_email', $user['email']);
        //       SessionManager::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
        //       SessionManager::set('user_role', $user['role']);
        //       SessionManager::set('is_authenticated', true);

        // TODO: Display success message using FlashMessage::success()
        //       Message: "Welcome back, {$user['first_name']}!"

        // TODO: Redirect based on role:
        //       If role is 'admin', redirect to 'admin.dashboard'
        //       If role is 'customer', redirect to 'user.dashboard'
        //       Hint: if ($user['role'] === 'admin') { ... } else { ... }
    }

    /**
     * Logout the current user (GET request).
     */
    public function logout(Request $request, Response $response, array $args): Response
    {
        // TODO: Destroy the session using SessionManager::destroy()

        // TODO: Display success message: "You have been logged out successfully."

        // TODO: Redirect to 'auth.login' route
    }

    /**
     * Display user dashboard (protected route).
     */
    public function dashboard(Request $request, Response $response, array $args): Response
    {
        // TODO: Create a $data array with 'title' => 'Dashboard'

        // TODO: Render 'user/dashboard.php' view and pass $data
    }
}
