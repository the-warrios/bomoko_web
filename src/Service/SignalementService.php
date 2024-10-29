<?php

namespace App\Service;

use App\Entity\Disaster;
use App\Entity\Report;
use App\Entity\ReportCategory;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class SignalementService
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;
    private VehiculeService $vehiculeService;
    private UserService $userService;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, VehiculeService $vehiculeService, UserService $userService) {
        $this->em = $em;
        $this->logger = $logger;
        $this->vehiculeService = $vehiculeService;
        $this->userService = $userService;
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
}