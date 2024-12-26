<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\ExceptionService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    private ExceptionService $exceptionService;
    private LoggerInterface $logger;

    private EntityManagerInterface $em;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(Request $request, #[CurrentUser] ?User $user, JWTTokenManagerInterface $JWTManager)
    {
    }

}