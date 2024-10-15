<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\ExceptionService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        $this->logger->info("# ApiLoginController > index : Start");
        // Si l'utilisateur est déjà connecté, retournez un message approprié

        // Récupérer l'email et le mot de passe de la requête
        $data = json_decode($request->getContent(), true);
        $email = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        $this->logger->info("# ApiLoginController > index : data", ["data" => $data]);

        // Valider les informations d'identification
        if (!$email || !$password) {
            return $this->json([
                'message' => 'Missing credentials',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Rechercher l'utilisateur par l'email
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        $this->logger->info("# ApiLoginController > index : user", ['user' => $user->getEmail()]);

        // Vérifier si l'utilisateur existe et si le mot de passe est valide
        if (!$user || !$this->userPasswordHasher->isPasswordValid($user, $password)) {
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json(
            $user
        , Response::HTTP_OK);
    }

}