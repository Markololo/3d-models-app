<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Models\TwoFactorAuthModel;
use App\Domain\Models\User;
use App\Domain\Models\UserModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use PDOException;
use Psr\Container\ContainerInterface;
use RobThree\Auth\TwoFactorAuth as TFA;
use RobThree\Auth\Providers\Qr\BaconQrCodeProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller for Two-Factor Authentication operations.
 */
class TwoFactorController extends BaseController
{
    /**
     * Display the 2FA setup page with QR code.
     */
    public function __construct(
        ContainerInterface $container,
        private TwoFactorAuthModel $twoFactorModel,
        private UserModel $userModel
    ) {
        parent::__construct($container);
    }

    public function showSetup(Request $request, Response $response): Response
    {
        // $user = $request->getAttribute('user');
        $userId = SessionManager::get('user_id');
        $userEmail = SessionManager::get('user_email');
        print("ID: " . $userId . " Email:" . $userEmail);
        // $userId = $user['id'];
        // $userEmail = $user['email'];F

        // Check if user already has 2FA enabled
        // Check if user already has 2FA enabled
        if ($this->twoFactorModel->isEnabled($userId)) {
            FlashMessage::error('2FA is already enabled.');
            return $this->redirect($request, $response, 'dashboard');
        }

        // $twoFactorModel = $this->container->get(TwoFactorAuthModel::class);
        // if ($twoFactorModel->isEnabled($userId)) {
        //     // setFlash
        //     // SessionManager::setFlash('error', '2FA is already enabled.');
        //     FlashMessage::error("2FA is already enabled.");
        //     return $this->redirect($request, $response, 'dashboard');
        // }

        // TODO: Create a QR code provider instance
        // HINT: Use BaconQrCodeProvider with parameters: (4, '#ffffff', '#000000', 'svg')
        // The parameters are: size, background color, foreground color, image format
        $qr = new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg');

        // TODO: Create a TFA (TwoFactorAuth) instance
        // HINT: Pass the QR provider and your app name (e.g., 'YourAppName') to the constructor
        $tfa = new TFA($qr, '3d-models-app');

        // TODO: Generate a new TOTP secret
        // HINT: Use the TFA instance's createSecret() method
        $secret = $tfa->createSecret(); // Replace with your implementation

        // Store secret in session temporarily (not in database yet)
        SessionManager::set('2fa_setup_secret', $secret);

        // TODO: Generate QR code as a data URI for display in an <img> tag
        // HINT: Use $tfa->getQRCodeImageAsDataUri($userEmail, $secret)
        // This returns a string like "data:image/svg+xml;base64,..." ready for img src

        $qrCodeDataUri = $tfa->getQRCodeImageAsDataUri($userEmail, $secret);
        print(" Secret: " . $secret);


        return $this->render($response, 'auth/2fa-setup.php', [
            'title' => 'Enable 2FA',
            'qrCodeDataUri' => $qrCodeDataUri,
            'secret' => $secret
        ]);
    }

    /**
     * Verify the code and enable 2FA.
     */
    public function verifyAndEnable(Request $request, Response $response): Response
    {
        // $user = $request->getAttribute('user');
        // $userId = $user['id'];
        // $userEmail = $user['email'];
        $userId = SessionManager::get('user_id');
        $userEmail = SessionManager::get('user_email');
        $data = $request->getParsedBody();
        $code = $data['code'] ?? '';

        // Get secret from session
        $secret = SessionManager::get('2fa_setup_secret');

        if (!$secret) {

            // SessionManager::setFlash('error', 'Setup session expired. Please try again.');
            FlashMessage::error('Setup session expired. Please try again.');
            return $this->redirect($request, $response, '2fa.setup');
        }

        // TODO: Create a QR code provider and TFA instance (same as showSetup)
        // HINT: Use BaconQrCodeProvider and TFA classes
        $qr = new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg');

        // TODO: Create a TFA (TwoFactorAuth) instance
        // HINT: Pass the QR provider and your app name (e.g., 'YourAppName') to the constructor
        $tfa = new TFA($qr, '3d-models-app');

        // TODO: Generate a new TOTP secret
        // HINT: Use the TFA instance's createSecret() method
        $secret = $tfa->createSecret();



        // TODO: Verify the user's code against the secret
        // HINT: Use $tfa->verifyCode($secret, $code) - returns true if valid
        $valid = $tfa->verifyCode($secret, $code); // Replace with your implementation


        if (!$valid) {
            // TODO: Regenerate QR code for retry (user entered wrong code)
            // HINT: Use $tfa->getQRCodeImageAsDataUri($userEmail, $secret)
            $qrCodeDataUri = $tfa->getQRCodeImageAsDataUri($userEmail, $secret);

            return $this->render($response, 'auth/2fa-setup.php', [
                'title' => 'Enable 2FA',
                'error' => 'Invalid verification code. Please try again.',
                'qrCodeDataUri' => $qrCodeDataUri,
                'secret' => $secret
            ]);
        }

        // TODO: Save secret to database and enable 2FA
        // Step 1: Get the TwoFactorAuth model from the container
        // Step 2: Create a new 2FA record: $twoFactorModel->create($userId, $secret)
        // Step 3: Enable 2FA for the user: $twoFactorModel->enable($userId)

        $twoFactorModel = $this->container->get(TwoFactorAuthModel::class);
        $twoFactorModel->create($userId, $secret);
        $twoFactorModel->enable($userId);
        // Clear the setup secret from session
        SessionManager::remove('2fa_setup_secret');

        FlashMessage::success('2FA has been enabled successfully!');
        return $this->redirect($request, $response, 'dashboard');
    }

    /**
     * Show the 2FA verification page (during login).
     */
    public function showVerify(Request $request, Response $response): Response
    {
        return $this->render($response, 'auth/2fa-verify.php', [
            'title' => 'Verify 2FA'
        ]);
    }

    /**
     * Verify 2FA code during login.
     */
    public function verify(Request $request, Response $response): Response
    {
        $userId = SessionManager::get('user_id');
        $data = $request->getParsedBody();
        $code = $data['code'] ?? '';

        // TODO: Get the user's TOTP secret from the database
        // Step 1: Get the TwoFactorAuth model from the container
        // Step 2: Use getSecret($userId) to retrieve the secret
        $twoFactorModel = $this->container->get(TwoFactorAuthModel::class);

        $secret =   $twoFactorModel->getSecret($userId);

        // TODO: Create a QR code provider and TFA instance

        // TODO: Create a QR code provider and TFA instance (same as showSetup)
        // HINT: Use BaconQrCodeProvider and TFA classes
        $qr = new BaconQrCodeProvider(4, '#ffffff', '#000000', 'svg');

        $tfa = new TFA($qr, '3d-models-app');


        // TODO: Verify the user's code against their stored secret
        // HINT: Use $tfa->verifyCode($secret, $code)
        $valid = $tfa->verifyCode($secret, $code); // Replace with your implementation

        if (!$valid) {
            // Track failed attempts
            $attempts = (SessionManager::get('2fa_attempts') ?? 0) + 1;
            SessionManager::set('2fa_attempts', $attempts);

            // Lockout after 5 failed attempts
            if ($attempts >= 5) {
                SessionManager::destroy();
                return $this->redirect($request, $response, 'auth.login');
            }

            return $this->render($response, 'auth/2fa-verify.php', [
                'title' => 'Verify 2FA',
                'error' => 'Invalid code. Please try again.'
            ]);
        }

        // Success! Mark 2FA as verified in session
        SessionManager::set('two_factor_verified', true);
        SessionManager::remove('2fa_attempts');

        // Regenerate session ID for security
        // if($trustedDevice)
        // {
        //  $this-saveTrustedDevice($request,$userId);
        // }

        session_regenerate_id(true);

        // Redirect to dashboard
        return $this->redirect($request, $response, 'dashboard');
    }

    /**
     * Disable 2FA for the user.
     */
    public function disable(Request $request, Response $response): Response
    {
        $userId = SessionManager::get('user_id');
        $userEmail = SessionManager::get('user_email');
        $data = $request->getParsedBody();
        $password = $data['password'] ?? '';

        // Verify password before disabling 2FA
        $validUser = $this->userModel->verifyCredentials($userEmail, $password);
        if (!$validUser) {
            return $this->render($response, 'auth/2fa-disable.php', [
                'title' => 'Disable 2FA',
                'error' => 'Invalid password.'
            ]);
        }

        // TODO: Disable 2FA in the database
        // Step 1: Get the TwoFactorAuth model from the container
        // Step 2: Call the disable($userId) method to disable 2FA
        FlashMessage::success('2FA has been disabled.');
        return $this->redirect($request, $response, 'dashboard');
    }

    /**
     * Show disable confirmation page.
     */
    public function showDisable(Request $request, Response $response): Response
    {
        return $this->render($response, 'auth/2fa-disable.php', [
            'title' => 'Disable 2FA'
        ]);
    }
}
