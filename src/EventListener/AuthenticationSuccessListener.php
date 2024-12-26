<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    private JWTTokenManagerInterface $JWTManager;

    public function __construct(JWTTokenManagerInterface $JWTManager)
    {
        $this->JWTManager = $JWTManager;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        if(method_exists($user, 'getId'))

        // Ajoutez des données au payload du token
        $customPayload = [
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(), // Ajout de l'email
            'roles' => $user->getRoles(),
        ];

        // Créez le token avec le nouveau payload
        $token = $this->JWTManager->createFromPayload($user, $customPayload);

        // Définissez le token dans la réponse
        $event->setData([
            'token' => $token,
        ]);
    }
}
