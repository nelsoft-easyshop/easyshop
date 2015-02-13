<?php

namespace EasyShop\Promo;

use EasyShop\Entities\EsPromo;
use EasyShop\Entities\EsPromoType;

class Estudyantrepreneur
{

    /**
     * Promo config
     *
     * @var mixed
     */
    private $promoConfig = array();

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Constructor
     * @param ConfigLoader $configLoader
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct($configLoader, \Doctrine\ORM\EntityManager $em)
    {
        $this->promoConfig = $configLoader->getItem('promo', 'Promo');
        $this->em = $em;
    }

    /**
     * Get previous rounds
     * @param array $rounds
     * @return array
     */
    private function __getPreviousRounds($rounds)
    {
        $date = new \DateTime;
        $dateToday = $date->getTimestamp();
        $round = false;
        $previousStartDate = '';
        $previousEndDate = '';
        $case = '';
        $limit = 0;
        $previousRound = '';
        $round = false;

        foreach ($rounds as $key => $data) {
            $startDate = strtotime($data['start']);
            $endDate = strtotime($data['end']);
            if ($dateToday >= $startDate && $dateToday < $endDate) {
                $round = $key;
                $case = $round;
                $limit = (int) $data['limit'];

                $keys = array_keys($rounds);
                $found_index = array_search($key, $keys);
                if ($found_index === true || $found_index !== 0) {
                    $previousRound = $keys[$found_index-1];
                    $previousStartDate = $rounds[$previousRound]['start'];
                    $previousEndDate = $rounds[$previousRound]['end'];
                }

                break;
            }
        }
        $data = [
            'round' => $round,
            'previousRound' => $previousRound,
            'case' => $case,
            'limit' => $limit,
            'previousStartDate' => $previousStartDate,
            'previousEndDate' => $previousEndDate
        ];

        return $data;
    }

    /**
     * Retrieves Students depending on date and school
     * @param $schools
     * @param $previousStartDate
     * @param $previousEndDate
     * @param $limit
     * @return mixed
     */
    private function __getStudentsByDateAndSchool($schools, $previousStartDate, $previousEndDate, $limit)
    {
        foreach ($schools as $school) {
            $schoolName = $school['name'];
            $students = $this->em->getRepository('EasyShop\Entities\EsStudent')
                                 ->getStudentsByDateAndSchool(
                                     $previousStartDate,
                                     $previousEndDate,
                                     $school,
                                     $limit
                                 );
            $result[$schoolName]['students'] = $students;
            $result[$schoolName]['isQualifiedInNextRound'] = 0;

            if ($students) {
                end($result[$schoolName]['students']);
                $lastKey = key($result[$schoolName]['students']);
                $studentsWithSameVote = $this->em->getRepository('EasyShop\Entities\EsStudent')
                                                 ->getStudentsByDateAndSchool(
                                                     $previousStartDate,
                                                     $previousEndDate,
                                                     $school,
                                                     PHP_INT_MAX,
                                                     $result[$schoolName]['students'][$lastKey]['vote'],
                                                     $result[$schoolName]['students'][$lastKey]['student']
                                                 );

                if ($studentsWithSameVote) {
                    $result[$schoolName]['students'] = array_merge($result[$schoolName]['students'], $studentsWithSameVote);
                }
            }

            $studentCount = count($result[$schoolName]['students']);

            if ($studentCount <= 3 && $studentCount !== 0) {
                $result[$schoolName]['isQualifiedInNextRound'] = 1;
            }
        }

        return $result;
    }

    /**
     * Get School and its student by date / round
     * @return array
     */
    public function getSchoolWithStudentsByRound()
    {
        $result = [];
        $rounds = $this->promoConfig[EsPromoType::ESTUDYANTREPRENEUR]['option'];
        $roundData = $this->__getPreviousRounds($rounds);

        switch($roundData['case']) {
            case 'first_round' :
                $students = $this->em->getRepository('EasyShop\Entities\EsStudent')->getAllStudents();

                foreach ($students as $student) {

                    if (!isset($result[$student['school']])) {
                        $result[$student['school']] = [];
                    }

                    $result[$student['school']][] = $student;
                }

                break;
            case 'second_round' :
                $schools = $this->em->getRepository('EasyShop\Entities\EsSchool')->getAllSchools();
                $result = $this->__getStudentsByDateAndSchool(
                                     $schools,
                                     $roundData['previousStartDate'],
                                     $roundData['previousEndDate'],
                                     $roundData['limit']
                                 );

                break;
            case 'inter_school_round':
                $firstRound = $rounds['first_round'];
                $schools = $this->em->getRepository('EasyShop\Entities\EsSchool')->getAllSchools();
                $secondRound = $this->__getStudentsByDateAndSchool(
                                         $schools,
                                         $firstRound['start'],
                                         $firstRound['end'],
                                         $firstRound['limit']
                                     );
                $secondRoundWinners = $this->__getStudentsByDateAndSchool(
                                                         $schools,
                                                         $roundData['previousStartDate'],
                                                         $roundData['previousEndDate'],
                                                         $roundData['limit']
                                                     );

                foreach ($secondRound as $key => $schools) {

                    if ($schools['isQualifiedInNextRound']) {
                        $secondRoundWinners[$key]['students'] = $schools['students'];
                    }

                }

                $result = $secondRoundWinners;

                break;
        }

        $result = [
            'schools_and_students' => $result,
            'round' => $roundData['round']
        ];

        return $result;
    }

    /**
     * Vote a student
     * @param $studentId
     * @param $memberId
     * @return EasyShop\Entities\EsPromo
     */
    public function voteStudent($studentId, $memberId)
    {
        $promo = new EsPromo();
        $promo->setMemberId($memberId);
        $promo->setStudentId($studentId);
        $promo->setPromoType(EsPromoType::ESTUDYANTREPRENEUR);
        $promo->setCreatedAt(new \DateTime('now'));
        $this->em->persist($promo);
        $this->em->flush();

        return $promo;
    }

    /**
     * Check is the user already voted
     * @param $memberId
     * @return EasyShop\Entities\EsPromo
     */
    public function isUserAlreadyVoted($memberId)
    {
        $roundData = $this->__getPreviousRounds();
        $rounds = $this->promoConfig[EsPromoType::ESTUDYANTREPRENEUR]['option'];
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('tblPromo')
                    ->from('EasyShop\Entities\EsPromo', 'tblPromo')
                    ->where('tblPromo.memberId = :memberId')
                    ->andWhere('tblPromo.promoType = :promoType')
                    ->andWhere('tblPromo.createdAt >= :startDate')
                    ->andWhere('tblPromo.createdAt < :endDate')
                    ->setParameter('memberId', $memberId)
                    ->setParameter('promoType', EsPromoType::ESTUDYANTREPRENEUR)
                    ->setParameter('startDate', $rounds[$roundData['round']]['start'])
                    ->setParameter('endDate', $rounds[$roundData['round']]['end'])
                    ->getQuery();

        return $query->getResult();
    }
}
