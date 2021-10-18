<?php

namespace App\Repository;

use App\Entity\TchatMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TchatMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TchatMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TchatMessage[]    findAll()
 * @method TchatMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TchatMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TchatMessage::class);
    }

    // /**
    //  * @return TchatMessage[] Returns an array of TchatMessage objects based on sender and receiver
    //  */
    public function findTchatHistory(int $fromId, int $toId)
    {
        return $this->createQueryBuilder('tm')
            ->setParameter('fromId', $fromId)
            ->setParameter('toId', $toId)
            ->where('tm.userFrom = :fromId AND tm.userTo = :toId') // sended
            ->orWhere('tm.userFrom = :toId AND tm.userTo = :fromId') // or received
            ->orderBy('tm.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
