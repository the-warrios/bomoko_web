<?php

namespace App\Service;

use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class VehiculeService
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;

    private ExceptionService $exceptionService;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, ExceptionService $exceptionService)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->exceptionService = $exceptionService;
    }

    public function findOneVehicule(string $plate)
    {
        $this->logger->info("# VehiculeService > findOneVehicule :", ["plaque" => $plate]);
        return $this->em->getRepository(Vehicule::class)->findOneBy(['plate' => $plate]);
    }

    public function findVehicules(string $vehiculeId)
    {
        //dd($vehiculeId);
        return $this->em->getRepository(Vehicule::class)->find($vehiculeId);
    }

    public function addVehicule(string $plate) : void
    {
        $vehicule = new Vehicule();
        $vehicule->setPlate($plate);

        $this->em->persist($vehicule);
        $this->em->flush();
    }

}