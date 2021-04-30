<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Activite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\This;

/**
 * @method Activite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activite[]    findAll()
 * @method Activite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Activite::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Activite[] Returns an array of Activite objects
    //  */

    public function findByType(String $value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.type = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Activite
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findSearch(SearchData $search): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('a')
            ->select('t','a')
            ->join('a.type','t');

        if(!empty($search->q)){
            $query = $query
                ->andWhere('a.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if(!empty($search->types)){
            $query = $query
            ->andWhere('t.id IN (:type)')
                ->setParameter('type', $search->types);
        }

        $query= $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            3
        );


    }
}
