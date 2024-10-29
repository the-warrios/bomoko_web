<?php

namespace App\Controller\Api;

use App\Service\ExtensionService;
use App\Service\SignalementService;
use App\Service\VehiculeService;
use phpDocumentor\Reflection\Types\This;
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

    private VehiculeService $vehiculeService;

    public function __construct(SignalementService $signalementService, LoggerInterface $logger, ExtensionService $extensionService, VehiculeService $vehiculeService)
    {
        $this->signalementService = $signalementService;
        $this->logger = $logger;
        $this->extensionService = $extensionService;
        $this->vehiculeService = $vehiculeService;
    }

    #[Route('/api/report', name: 'api_signalement', methods: ['POST'])]
    public function index(Request $request) :JsonResponse
    {
        $this->logger->info("# ApiReportController > index > start");

        $description = $request->request->get('description');
        $plate = $request->request->get('plate');
        $categoryId = $request->request->get('categoryId');
        $userId = $request->headers->get('user-id');

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

       // dd($description, $plate, $categoryId, $userId, $image, $video);

        // Recherche du véhicule par sa plaque
        $vehicule = $this->vehiculeService->findOneVehicule($plate);

        if (!$vehicule) {
            $this->logger->info("# ApiReportController > index > add new vehicule", ["plaque" => $plate]);

            // Ajoute le nouveau véhicule avec la plaque donnée
            $this->vehiculeService->addVehicule($plate);

            // Recharge le véhicule fraîchement ajouté
            $vehicule = $this->vehiculeService->findOneVehicule($plate);
        }

        // À ce stade, $vehicule ne devrait plus être null
        $vehiculeId = $vehicule ? $vehicule->getId() : null;

        // Log des données reçues
        $this->logger->info("# ApiReportController > index > data", [
            "description" => $description,
            "image" => $image?->getClientOriginalName(),
            "video" => $video?->getClientOriginalName()
        ]);

        //dd($vehiculeId);

        if (!$description || !$image || !$video || !$vehiculeId || !$categoryId || !$userId)
        {
            return $this->json(
                ["message" => "Données incomplètes"],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->signalementService->addSignalement($description, $image, $video, $vehiculeId, $categoryId, $userId);

        return $this->json(
            ['message' => 'Signalement Enregistré'],
            Response::HTTP_OK,
            [
                'Bomoko' => "Oslo Jiwe"
            ]
        );
    }

    #[Route('/api/report/category', name: "api_report_category", methods: ['GET'])]
    public function getReportCategory() : JsonResponse
    {
        $data = $this->signalementService->getReportCategory();

        //dd($data);

        return $this->json(
            $data,
            Response::HTTP_OK,
            [],
            ['groups' => ['reportCategory:read']]
        );
    }

    #[Route('/api/report/category/disaster', name: 'api_report_category_disaster', methods: ['GET'])]
    public function getDisaster() : JsonResponse
    {
        $data = $this->signalementService->getDisaster();

        return $this->json(
          $data,
          Response::HTTP_OK
        );
    }
}