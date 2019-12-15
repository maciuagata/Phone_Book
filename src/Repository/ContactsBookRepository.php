<?php

namespace App\Repository;

use App\Entity\ContactsBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ContactsBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactsBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactsBook[]    findAll()
 * @method ContactsBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactsBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactsBook::class);
    }

    // /**
    //  * @return ContactsBook[] Returns an array of ContactsBook objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactsBook
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
