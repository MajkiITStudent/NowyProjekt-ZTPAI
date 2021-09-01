<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findByExampleField($value): array
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



    /**
     * @throws NonUniqueResultException
     */
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllCompleteEvents(){
        return $this->createQueryBuilder('e')
            ->where('e.people_needed = 0')
            ->orderBy('e.uploaded_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllPastEvents(){
        $now = new \DateTime('now');
        return $this->createQueryBuilder('e')
            ->where('e.event_datetime < :now')
            ->setParameter('now', $now)
            ->orderBy('e.uploaded_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllUpcomingEvents(){
        $now = new \DateTime('now');
        return $this->createQueryBuilder('e')
            ->where('e.event_datetime > :now')
            ->setParameter('now', $now)
            ->orderBy('e.uploaded_at', 'DESC')
            ->getQuery()
            ->getResult();
    }


}
