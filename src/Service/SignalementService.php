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

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger) {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function addSignalement($description, $image, $video): void
    {
        $report = new Report();
        $report->setDescription($description);
        $report->setStatus("En attente");
        $report->setImageFile($image);
        $report->setVideoFile($video);

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
}