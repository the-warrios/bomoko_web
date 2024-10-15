<?php

namespace App\Service;

use App\Entity\Report;
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

    public function addSignalement($description, $image, $video)
    {
        $report = new Report();
        $report->setDescription($description);
        $report->setStatus(null);
        $report->setImageFile($image);
        $report->setVideoFile($video);

        $this->em->persist($report);
        $this->em->flush();

    }
}