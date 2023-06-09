<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 *
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function save(Wish $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Wish $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findWishes(){
        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder->addOrderBy('w.dateCreated', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function findPublishedWishesWithCategories(): ?array{
        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder->join('w.category', 'c')->addSelect('c');
        $queryBuilder->andWhere('w.isPublished=1');
        $queryBuilder->orderBy('w.dateCreated', 'DESC');

        $query=$queryBuilder->getQuery();
        return $query->getResult();
    }
}