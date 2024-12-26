<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Report>
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    //    /**
    //     * @return Report[] Returns an array of Report objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Report
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Récupère les rapports d'un utilisateur avec des critères.
     */
    public function findByUserAndDateRange(
        string $userId,
        ?\DateTimeInterface $dateDebut = null,
        ?\DateTimeInterface $dateFin = null,
        int $offset = 0,
        int $limit = 10
    ): array {

        // Conversion de l'UUID en format binaire hexadécimal
        $uuid = hex2bin(str_replace('-', '', $userId));  // Retirer les tirets et convertir en binaire

        $qb = $this->createQueryBuilder('r')
            ->where('r.user_report = :userId')
            ->setParameter('userId', $uuid);

        if ($dateDebut) {
            $qb->andWhere('r.dateCreated >= :dateDebut')
                ->setParameter('dateDebut', $dateDebut);
        }

        if ($dateFin) {
            $qb->andWhere('r.dateCreated <= :dateFin')
                ->setParameter('dateFin', $dateFin);
        }

        return $qb->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('r.dateCreated', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
