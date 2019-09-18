<?php

namespace App\Repository;

use App\Entity\PasswordForget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PasswordForget|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordForget|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordForget[]    findAll()
 * @method PasswordForget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordForgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordForget::class);
    }

    // /**
    //  * @return PasswordForget[] Returns an array of PasswordForget objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PasswordForget
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
