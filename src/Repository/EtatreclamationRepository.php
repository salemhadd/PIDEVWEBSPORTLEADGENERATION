<?php

namespace App\Repository;

use App\Entity\Etatreclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etatreclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etatreclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etatreclamation[]    findAll()
 * @method Etatreclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatreclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etatreclamation::class);
    }

    // /**
    //  * @return Etatreclamation[] Returns an array of Etatreclamation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etatreclamation
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
