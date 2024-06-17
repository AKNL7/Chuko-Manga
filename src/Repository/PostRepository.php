<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findLatestPosts(int $limit = 2): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isValid = :valid')
            ->setParameter('valid', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findBestPricedPosts(): ?array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isValid = :valid')
            ->setParameter('valid', true)
            ->orderBy('p.postPrice', 'ASC')
            // ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

        public function findApprovedPosts(): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.isValid = :isValid')
                ->setParameter('isValid', true)
                ->getQuery()
                ->getResult();
        }

    public function findByCategory(Category $category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.postCategory = :category')
            ->setParameter('category', $category)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
