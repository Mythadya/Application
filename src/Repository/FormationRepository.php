<?php

// src/Repository/FormationRepository.php
namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Custom method to find all formations with relations
     */
    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.interruptions', 'i')
            ->leftJoin('f.periodEnEntreprises', 'p')
            ->leftJoin('f.formateurs', 'form')
            ->addSelect('i', 'p', 'form')
            ->getQuery()
            ->getResult();
    }
}
