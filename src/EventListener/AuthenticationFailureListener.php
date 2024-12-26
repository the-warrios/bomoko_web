<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationFailureListener
{
    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        $data = [
            'Application' => 'Bomoko'
        ];

        $response = new JWTAuthenticationFailureResponse('Identifiants incorrects, verifier que les username/password sont corrects', JsonResponse::HTTP_UNAUTHORIZED);
        $response->setData($data);

        $event->setResponse($response);
    }
}