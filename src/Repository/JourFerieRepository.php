<?php


namespace App\Repository;

use App\Entity\JourFerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class JourFerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JourFerie::class);
    }

    /**
     * @return string[] Array of distinct zone values
     */
    public function findDistinctZones(): array
    {
        return $this->createQueryBuilder('j')
            ->select('DISTINCT j.zone')
            ->orderBy('j.zone', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * @return int[] Array of distinct year values
     */
    public function findDistinctAnnees(): array
    {
        return $this->createQueryBuilder('j')
            ->select('DISTINCT j.annee')
            ->where('j.annee IS NOT NULL')
            ->orderBy('j.annee', 'DESC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * Finds holidays between two dates, optionally filtered by zone
     *
     * @param \DateTimeInterface $start Start date
     * @param \DateTimeInterface $end End date
     * @param string|null $zone Optional zone filter
     * @return JourFerie[]
     */
    public function findBetweenDates(
        \DateTimeInterface $start, 
        \DateTimeInterface $end,
        ?string $zone = null
    ): array {
        $qb = $this->createQueryBuilder('j')
            ->where('j.date BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('j.date', 'ASC');

        if ($zone) {
            $qb->andWhere('j.zone = :zone')
               ->setParameter('zone', $zone);
        }

        return $qb->getQuery()->getResult();
    }
}