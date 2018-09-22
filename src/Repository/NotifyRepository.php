<?php

namespace App\Repository;

use App\Entity\Notify;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Notify|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notify|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notify[]    findAll()
 * @method Notify[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotifyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notify::class);
    }

//    /**
//     * @return Notify[] Returns an array of Notify objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notify
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
