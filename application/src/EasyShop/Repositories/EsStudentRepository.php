<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;

class EsStudentRepository extends EntityRepository
{

    /**
     * Get all students
     * @return array
     */
    public function getAllStudents()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select("tblStudent.idStudent, tblSchool.idSchool, tblStudent.name AS student, COALESCE(tblStudent.description, '') as description, tblSchool.name AS school")
                    ->from('EasyShop\Entities\EsStudent', 'tblStudent')
                    ->leftJoin('EasyShop\Entities\EsSchool', 'tblSchool', 'WITH', 'tblSchool.idSchool = tblStudent.school')
                    ->groupBy('tblStudent.idStudent')
                    ->orderBy('tblStudent.idStudent')
                    ->getQuery();

        return $query->getResult();
    }

    /**
     * Get Students by Date and School
     * @param $startDate
     * @param $endDate
     * @param $schoolName
     * @param $maxResult
     * @param $vote
     * @param $excludeStudentName
     * @return array
     */
    public function getStudentsByDateAndSchool($startDate, $endDate, $schoolName, $maxResult = 3, $vote = 0, $excludeStudentName = false)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('tblStudent.idStudent, tblSchool.idSchool, tblStudent.name AS student, tblSchool.name AS school, count(tblMember.idMember) as vote')
                    ->from('EasyShop\Entities\EsPromo', 'tblPromo')
                    ->leftJoin('EasyShop\Entities\EsMember', 'tblMember', 'WITH', 'tblMember.idMember = tblPromo.memberId')
                    ->leftJoin('EasyShop\Entities\EsStudent', 'tblStudent', 'WITH', 'tblStudent.idStudent = tblPromo.studentId')
                    ->leftJoin('EasyShop\Entities\EsSchool', 'tblSchool', 'WITH', 'tblSchool.idSchool = tblStudent.school')
                    ->where('tblPromo.createdAt >= :startDate')
                    ->andWhere('tblPromo.createdAt < :endDate')
                    ->andWhere('tblSchool.name = :school');

        if ($vote > 0 && $excludeStudentName) {
            $query = $qb->andWhere('tblStudent.name != :student')
                        ->setParameter('student', $excludeStudentName);
        }

        $query = $qb->setParameter('startDate', $startDate)
                    ->setParameter('endDate', $endDate)
                    ->setParameter('school', $schoolName)
                    ->groupBy('tblStudent.idStudent');

        if ($vote > 0 && $excludeStudentName) {
            $query = $qb->having('count(tblMember.idMember) = :vote')
                        ->setParameter('vote', $vote);
        }

        $query = $qb->orderBy('vote', 'DESC')
                    ->setMaxResults($maxResult)
                    ->getQuery();

        return $query->getResult();
    }

}
