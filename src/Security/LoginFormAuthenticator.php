<?php
// src/Security/LoginFormAuthenticator.php
namespace App\Security;

use Symfony\Component\Security\Core\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginFormAuthenticator implements AuthenticatorInterface
{
    private $router;
    private $passwordEncoder;
    private $userRepository;

    public function __construct(RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request)
    {
        // Récupérer les données du formulaire de connexion
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        // Récupérer l'utilisateur de la base de données
        $user = $this->userRepository->findOneBy(['username' => $username]);

        // Si l'utilisateur n'existe pas ou si le mot de passe est incorrect, on lance une exception
        if (!$user) {
            throw new AuthenticationException('Utilisateur non trouvé');
        }

        // Vérifier que le mot de passe est correct
        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new AuthenticationException('Mot de passe incorrect');
        }

        // Si l'utilisateur est trouvé et authentifié, créer un token
        return new UsernamePasswordToken($user, $password, 'main', $user->getRoles());
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Gérer l'échec de l'authentification, par exemple afficher un message d'erreur
        return new Response('Authentication failed: ' . $exception->getMessage(), Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, UsernamePasswordToken $token, $providerKey): ?Response
    {
        // Rediriger l'utilisateur après une authentification réussie
        return new Response('Authentication successful', Response::HTTP_OK);
    }
}
