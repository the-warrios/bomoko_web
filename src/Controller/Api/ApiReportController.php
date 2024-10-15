<?php

namespace App\Controller\Api;

use App\Service\ExtensionService;
use App\Service\SignalementService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiReportController extends AbstractController
{
    private SignalementService $signalementService;
    private LoggerInterface $logger;

    private ExtensionService $extensionService;

    public function __construct(SignalementService $signalementService, LoggerInterface $logger, ExtensionService $extensionService)
    {
        $this->signalementService = $signalementService;
        $this->logger = $logger;
        $this->extensionService = $extensionService;
    }

    #[Route('/api/report', name: 'api_signalement', methods: ['POST'])]
    public function index(Request $request) :JsonResponse
    {
        $this->logger->info("# ApiReportController > index > start");

        $description = $request->request->get('description');


        // Récupérer le fichier image et le fichier vidéo depuis la requête
        /** @var UploadedFile|null $imageFile */
        $image = $request->files->get('image');
        /** @var UploadedFile|null $videoFile */
        $video = $request->files->get('video');

        // Obtenez l'extension d'origine des fichiers
        $imageExtension = $image ? $image->getClientOriginalExtension() : null;
        $videoExtension = $video ? $video->getClientOriginalExtension() : null;

        // Validation de l'extension du fichier vidéo
        if ($video && !$this->extensionService->isValidVideoFile($videoExtension)) {
            return $this->json([
                'message' => 'Invalid video file type. Only .mp4, .avi, .mov files are allowed.',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Validation de l'extension du fichier image
        if ($image && !$this->extensionService->isValidImageFile($imageExtension)) {
            return $this->json([
                'message' => 'Invalid image file type. Only JPEG and PNG are allowed.',
            ], Response::HTTP_BAD_REQUEST);
        }

        //dd($video);

        // Log des données reçues
        $this->logger->info("# ApiReportController > index > data", [
            "description" => $description,
            "image" => $image?->getClientOriginalName(),
            "video" => $video?->getClientOriginalName()
        ]);

        if (!$description || !$image || !$video)
        {
            return $this->json(
                ["message" => "Données incomplètes"],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->signalementService->addSignalement($description, $image, $video);

        return $this->json(
            ['message' => 'Signalement Enregistré'],
            Response::HTTP_OK,
            [
                'Bomoko' => "Oslo Jiwe"
            ]
        );
    }
}