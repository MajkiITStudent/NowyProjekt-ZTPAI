<?php

namespace App\Repository;

use App\Entity\EventParticipants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventParticipants|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventParticipants|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventParticipants[]    findAll()
 * @method EventParticipants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventParticipantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventParticipants::class);
    }

    // /**
    //  * @return EventParticipants[] Returns an array of EventParticipants objects
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


    /**
     * @throws NonUniqueResultException
     */
    public function findOneBySomeField($value): ?EventParticipants
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.event = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
