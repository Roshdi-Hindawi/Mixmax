<?php

namespace MainBundle\Repository;

/**
 * WorkerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkerRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByActiveWorker()
    {
        $qb = $this->createquerybuilder('u')
                    ->select('u')
                    ->where('u.status = :stat')
                    ->setParameter('stat', 'En Poste');
        return $qb->getQuery()->getResult();
    }

    public function findByFacility($facility)
    {
        $qb = $this->createquerybuilder('u')
                    ->select('u')
                    ->where('u.facility = :facility')
                    ->andwhere('u.status = :stat')
                    //->join('u.facility', 'facility')
                    ->setParameters(['facility' => $facility, 'stat' => 'En Poste']);
        return $qb->getQuery()->getResult();
    }
}