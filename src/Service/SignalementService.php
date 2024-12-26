<?php

namespace App\Service;

use App\Entity\Disaster;
use App\Entity\Report;
use App\Entity\ReportCategory;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Psr\Log\LoggerInterface;

class SignalementService
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;
    private VehiculeService $vehiculeService;
    private UserService $userService;
    private ToolsService $toolsService;

    private ReportRepository $reportRepository;

    public function __construct(EntityManagerInterface $em,
                                LoggerInterface $logger,
                                VehiculeService $vehiculeService,
                                UserService $userService,
                                ToolsService $toolsService,
                                ReportRepository $reportRepository
    ) {
        $this->em = $em;
        $this->logger = $logger;
        $this->vehiculeService = $vehiculeService;
        $this->userService = $userService;
        $this->toolsService = $toolsService;
        $this->reportRepository = $reportRepository;
    }

    public function addSignalement($description, $image, $video, $vehiculeId, $categoryId, $userId): void
    {
        $report = new Report();
        $report->setDescription($description);
        $report->setStatus("En attente");
        $report->setImageFile($image);
        $report->setVideoFile($video);
        $vehicule = $this->vehiculeService->findVehicules($vehiculeId);
        $report->setReportVehicule($vehicule);
        $reportCategory = $this->getReportCategoryById($categoryId);
        $report->setReportCategory($reportCategory);
        $user = $this->userService->getUserById($userId);
        $report->setUserReport($user);

        //dd($report);

        $this->em->persist($report);
        $this->em->flush();

    }

    public function getReportCategory() : array
    {
        return $this->em->getRepository(ReportCategory::class)->findAll();
    }

    public function getDisaster() : array
    {
        return $this->em->getRepository(Disaster::class)->findAll();
    }

    public function getReportCategoryById(int $id) : ReportCategory
    {
        return $this->em->getRepository(ReportCategory::class)->find($id);
    }

    /**
     * @throws JWTDecodeFailureException
     */
    public function getReportRegister(array $data, string $token): array
    {
        // Validation des paramètres
        if (empty($data['dateDebut'])) {
            throw new \InvalidArgumentException('Le paramètre "dateDebut" est requis et ne peut pas être vide.');
        }

        if (empty($data['dateFin'])) {
            throw new \InvalidArgumentException('Le paramètre "dateFin" est requis et ne peut pas être vide.');
        }

        if (!isset($data['offset']) || !is_numeric($data['offset']) || $data['offset'] < 0) {
            throw new \InvalidArgumentException('Le paramètre "offset" doit être un entier positif ou nul.');
        }

        try {
            $dateDebut = new \DateTime($data['dateDebut']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Le paramètre "dateDebut" doit être une date valide au format Y-m-d.');
        }

        try {
            $dateFin = new \DateTime($data['dateFin']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Le paramètre "dateFin" doit être une date valide au format Y-m-d.');
        }

        // Vérifier que la date de début est antérieure à la date de fin
        if ($dateDebut > $dateFin) {
            throw new \InvalidArgumentException('La date "dateDebut" doit être antérieure ou égale à "dateFin".');
        }

        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);  // Retirer "Bearer " du début
        }

        // Récupération de l'ID utilisateur à partir du token
        $userId = $this->toolsService->getUserIdByToken($token);

        if (!$userId) {
            throw new \RuntimeException('Impossible de récupérer l\'ID utilisateur depuis le token.');
        }

        // Récupération des reports
        $reports = $this->reportRepository->findByUserAndDateRange($userId, $dateDebut, $dateFin, (int)$data['offset']);

        if (empty($reports)) {
            return [];
        }

        // Retourner les résultats formatés
        return array_map(function ($report) {
            return [
                'id' => $report->getId(),
                'description' => $report->getDescription(),
                'video' => $this->toolsService->getVideoUrl($report->getVideo()),
                'image' => $this->toolsService->getImageUrl($report->getImage()),
                'geometry' => $report->getGeometry(),
                'category' => $report->getReportCategory()->getLabel(),
                'dateCreated' => $report->getDateCreated()->format('Y-m-d H:i:s'),
                'status' => $report->getStatus(),
            ];
        }, $reports);
    }
}