<?php

namespace MainBundle\Repository;

/**
 * SalaryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SalaryRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByDate()
    {
        $qb = $this->createquerybuilder('u')
                   ->select('u')
                   ->where('u.year = :year')
                   ->andWhere('u.month = :month')
                   ->setParameter('year', date('Y'))
                   ->setParameter('month', date('m'));
        return $qb->getQuery()->getResult();
    }

    public function findByMonth($time)
    {
        $qb = $this->createquerybuilder('u')
                   ->select('u')
                   ->where('u.year = :year')
                   ->andwhere('u.month = :month')
                   ->setParameters(['month'=>date('m', $time), 'year'=>date('Y', $time)]);
        return $qb->getQuery()->getResult();
    }

    public function findByPayDate()
    {
        $qb = $this->createquerybuilder('u')
                   ->select('u')
                   ->where('u.year = :year')
                   ->andwhere('u.month = :month')
                   ->setParameters(['month'=>intval(date('m'))-1, 'year'=>date('Y')]);
        return $qb->getQuery()->getResult();
    }
}