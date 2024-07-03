<?php

namespace App\Repository;

use App\Entity\Secret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Secret>
 */
class SecretRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Secret::class);
    }

    public function findOneActiveSecretByHash(string $hash): ?Secret
    {
        $qb = $this->createQueryBuilder('s');

        $currentDate = new \DateTime();

        return $qb->select('s')
            ->where('s.hash = :hash')
            ->andWhere($qb->expr()->gt('s.expiresAt', ':currentDate'))
            ->andWhere($qb->expr()->gt('s.remainingViews', 0))
            ->setParameter('hash', $hash)
            ->setParameter('currentDate', $currentDate->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
