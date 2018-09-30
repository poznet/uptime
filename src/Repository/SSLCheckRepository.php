<?php

namespace App\Repository;

use App\Entity\SSLCheck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SSL|null find($id, $lockMode = null, $lockVersion = null)
 * @method SSL|null findOneBy(array $criteria, array $orderBy = null)
 * @method SSL[]    findAll()
 * @method SSL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SSLCheckRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SSLCheck::class);
    }

//    /**
//     * @return SSL[] Returns an array of SSL objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SSL
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
