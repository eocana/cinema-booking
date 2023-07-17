<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\DBAL\DBALException;

abstract class BaseRepository
{
    private ManagerRegistry $managerRegistry;
    protected Connection $connection;
    protected ObjectRepository $objectRepository;

    public function __construct(ManagerRegistry $managerRegistry, Connection $connection)
    {

     $this->connection = $connection;
     $this->managerRegistry = $managerRegistry;
     $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }

    abstract protected static function entityClass(): string;

    /**
     * @param object $entity
     * @throws ORMException
     */
    public function persistEntity(object $entity): void{
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException
     */
    public function flushData(): void{
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveEntity(object $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeEntity(object $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws Exception
     */
    protected function exectueFetchQuery(string $query, array $params = []): array
    {
        return $this->connection->executeQuery($query, $params)->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    protected function executeQuery(string $query, array $params = [] )
    {
        $this->connection->executeQuery($query, $params);
    }

    /**
     * @return ObjectManager
     */
    protected function getEntityManager()
    {
        $entityManager = $this->managerRegistry->getManager();

        if ($entityManager->isOpen()) {
            return $entityManager;
        } else {
            return $this->managerRegistry->resetManager();
        }
    }
}