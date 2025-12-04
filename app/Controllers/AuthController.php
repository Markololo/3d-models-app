<?php

namespace App\Controllers;

use App\Domain\Models\UserModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RobThree\Auth\TwoFactorAuth;
use App\Domain\Models\TwoFactorAuthModel;

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

        //------------- Start FROM 2FA -----------------------
        // Check if user has 2FA enabled
        // $twoFactorModel = $this->container->get(TwoFactorAuth::class);
        // $has2FA = $twoFactorModel->isEnabled($user['id']);
        // // Set session data using SessionManager (same pattern as Auth Part 2)
        // SessionManager::set('user_id', $user['id']);
        // SessionManager::set('user_email', $user['email']);
        // SessionManager::set('user_first_name', $user['first_name']);
        // SessionManager::set('user_last_name', $user['last_name']);
        // SessionManager::set('is_authenticated', true);
        // SessionManager::set('requires_2fa', $has2FA);
        // SessionManager::set('two_factor_verified', !$has2FA);
        // Auto-verified if no 2FA
        //------------- End FROM 2FA -------------------------

        // TODO: Create a $data array with 'title' => 'Login'
        $data =
            [
                'page_title' => 'Login'
            ];

        // TODO: Render 'auth/login.php' view and pass $data
        return $this->render($response, 'auth/login.php', $data);
    }

    /**
     * Process login form submission (POST request).
     */
    public function authenticate(Request $request, Response $response, array $args): Response
    {
        // TODO: Get form data using getParsedBody()
        $formData = $request->getParsedBody();
        $identifier = $formData["identifier"]; //or email
        $password = $formData["password"];


        //? 2) Start validation:
        $errors = [];

        if (empty($identifier) || empty($password)) {
            $errors[] = "All fields are required.";
        }


        if (!empty($errors)) {
            FlashMessage::error("Please enter the correct login info!");
            return $this->redirect($request, $response, 'auth.login');
        }

        $user = $this->userModel->verifyCredentials($identifier, $password);

        // Check if authentication was successful
        // TODO: If $user is null (authentication failed):

        if ($user == null) {
            // $errors[] = "Invalid credentials. Please try again.";
            FlashMessage::error("Invalid credentials. Please try again.");
            return $this->redirect($request, $response, 'auth.login');
        }

        // Authentication successful - create session
        // pass?
        // TODO: Store user data in session using SessionManager:
        SessionManager::set('user_id', $user['id']);
        SessionManager::set('user_email', $user['email']);
        SessionManager::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
        SessionManager::set('user_role', $user['role']);
        SessionManager::set('is_authenticated', true);

        //------------- Start FROM 2FA -----------------------
        // Check if user has 2FA enabled
        $twoFactorModel = $this->container->get(TwoFactorAuth::class);
        $has2FA = $twoFactorModel->isEnabled($user['id']);
        // Set session data using SessionManager (same pattern as Auth Part 2)
        SessionManager::set('user_id', $user['id']);
        SessionManager::set('user_email', $user['email']);
        SessionManager::set('user_first_name', $user['first_name']);
        SessionManager::set('user_last_name', $user['last_name']);
        SessionManager::set('is_authenticated', true);
        SessionManager::set('requires_2fa', $has2FA);
        SessionManager::set('two_factor_verified', !$has2FA);
        // Auto-verified if no 2FA
        //------------- End FROM 2FA -------------------------

        // TODO: Display success message using FlashMessage::success()
        FlashMessage::success("Welcome back, {$user['first_name']}!");
        // TODO: Redirect based on role:
        //       If role is 'admin', redirect to 'admin.dashboard'
        //       If role is 'customer', redirect to 'user.dashboard'
        if ($user['role'] === 'admin') {
            // return $this->redirect($request, $response, 'admin.dashboard');
            return $this->redirect($request, $response, 'dashboard.index');
        } else {

            return $this->redirect($request, $response, 'user.dashboard');
        }
    }




    /**
     * Logout the current user (GET request).
     */
    public function logout(Request $request, Response $response, array $args): Response
    {
        // TODO: Destroy the session using SessionManager::destroy()
        SessionManager::destroy();
        // TODO: Display success message: "You have been logged out successfully."
        FlashMessage::success("You have been logged out successfully.");

        // TODO: Redirect to 'auth.login' route
        return $this->redirect($request, $response, 'auth.login');
    }

    /**
     * Display user dashboard (protected route).
     */
    public function dashboard(Request $request, Response $response, array $args): Response
    {
        // // TODO: Create a $data array with 'title' => 'Dashboard'
        // $data =
        //     [
        //         'page_title' => 'Dashboard'
        //     ];

        // // TODO: Render 'user/dashboard.php' view and pass $data
        // return $this->render($response, 'user/dashboard.php', $data);

        $userId = SessionManager::get('user_id');
        $twoFactorModel = $this->container->get(TwoFactorAuthModel::class);
        $has2FA = $twoFactorModel->isEnabled($userId);

        return $this->render($response, 'dashboard.php', [
            'page_title' => 'Dashboard',
            'has2FA' => $has2FA
        ]);
    }
}
