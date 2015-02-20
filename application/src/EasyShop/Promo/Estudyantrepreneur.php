<?php

namespace EasyShop\Promo;

use EasyShop\Entities\EsPromo;
use EasyShop\Entities\EsPromoType;

class Estudyantrepreneur
{
    const MAX_NUM_OF_STUDENT = 3;

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
     * @return array
     */
    private function __getPreviousRounds()
    {
        $rounds = $this->promoConfig[EsPromoType::ESTUDYANTREPRENEUR]['option'];
        $date = new \DateTime;
        $dateToday = $date->getTimestamp();
        $previousStartDate = '';
        $previousEndDate = '';
        $case = '';
        $limit = 0;
        $previousRound = '';
        $round = '';
        $keys = array_keys($rounds);
        end($keys);

        foreach ($rounds as $key => $data) {
            $startDate = strtotime($data['start']);
            $endDate = strtotime($data['end']);
            $foundIndex = array_search($key, $keys);
            $isPromoStart = $dateToday >= $startDate && $dateToday < $endDate;

            if ($foundIndex !== 0) {
                $previousRound = $dateToday > $endDate && $foundIndex === key($keys) ? $keys[$foundIndex] : $keys[$foundIndex-1];
                $previousStartDate = $rounds[$previousRound]['start'];
                $previousEndDate = $rounds[$previousRound]['end'];
            }

            $showSuccessPage = $previousEndDate && ($dateToday > strtotime($previousEndDate) && $dateToday < $startDate);

            if ($isPromoStart || $showSuccessPage) {
                $round = $key;
                $case = $round;
                $limit = (int) $data['limit'];

                break;
            }
        }

        $isPromoEnded = !$isPromoStart && $previousRound === $keys[key($keys)];
        $data = [
            'round' => $round,
            'previousRound' => $previousRound,
            'case' => $case,
            'limit' => $limit,
            'previousStartDate' => $previousStartDate,
            'previousEndDate' => $previousEndDate,
            'showSuccessPage' => $showSuccessPage,
            'isPromoEnded' => $isPromoEnded
        ];

        return $data;
    }

    /**
     * Retrieves Students depending on date and school
     * @param $schools
     * @param $previousStartDate
     * @param $previousEndDate
     * @param $limit
     * @param $getStudentWithSameVote
     * @return mixed
     */
    private function __getStudentsByDateAndSchool($schools, $previousStartDate, $previousEndDate, $limit, $getStudentWithSameVote = false)
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
            $result[$schoolName]['isQualifiedInNextRound'] = false;

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

                if ($studentsWithSameVote && $getStudentWithSameVote) {
                    $result[$schoolName]['students'] = array_merge($result[$schoolName]['students'], $studentsWithSameVote);
                }
            }

            $studentCount = count($result[$schoolName]['students']);

            if ($studentCount <= self::MAX_NUM_OF_STUDENT && $studentCount !== 0) {
                $result[$schoolName]['isQualifiedInNextRound'] = true;
            }
        }

        return $result;
    }

    /**
     * Get Total votes per school
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function __getTotalVotesByDate($startDate, $endDate)
    {
        $result = [];
        $totalVotesPerSchool = $this->em->getRepository('EasyShop\Entities\EsPromo')
                                        ->getTotalVotesByDate(
                                            $startDate,
                                            $endDate
                                        );

        foreach ($totalVotesPerSchool as $school) {
            $result[$school['name']] = $school['vote'];
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
        $roundData = $this->__getPreviousRounds();

        switch($roundData['case']) {
            case 'first_round' :
                $students = $this->em->getRepository('EasyShop\Entities\EsStudent')->getAllStudents();

                foreach ($students as $student) {

                    if (!isset($result[$student['school']])) {
                        $result[$student['school']] = [];
                    }

                    $result[$student['school']]['students'][] = $student;
                }

                break;
            case 'second_round' :
                $firstRound = $rounds['first_round'];
                $secondRound = $rounds['second_round'];
                $schools = $this->em->getRepository('EasyShop\Entities\EsSchool')->getAllSchools();
                $result = $this->__getStudentsByDateAndSchool(
                                     $schools,
                                     $firstRound['start'],
                                     $firstRound['end'],
                                     $secondRound['limit'],
                                     true
                                 );

                break;
            case 'inter_school_round':
                $firstRound = $rounds['first_round'];
                $secondRound = $rounds['second_round'];
                $schools = $this->em->getRepository('EasyShop\Entities\EsSchool')->getAllSchools();
                $qualifiedToSecondRound = $this->__getStudentsByDateAndSchool(
                                                     $schools,
                                                     $firstRound['start'],
                                                     $firstRound['end'],
                                                     $secondRound['limit'],
                                                     true
                                                 );
                $secondRoundWinners = $this->__getStudentsByDateAndSchool(
                                                 $schools,
                                                 $secondRound['start'],
                                                 $secondRound['end'],
                                                 $secondRound['limit'],
                                                 true
                                             );

                foreach ($qualifiedToSecondRound as $key => $schools) {

                    if ($schools['isQualifiedInNextRound']) {
                        $secondRoundWinners[$key]['students'] = $schools['students'];
                    }

                }

                $result = $secondRoundWinners;

                break;
        }

        $result = [
            'schools_and_students' => $result,
            'round' => $roundData['round'],
            'showSuccessPage' => $roundData['showSuccessPage'],
            'isPromoEnded' => $roundData['isPromoEnded'],
            'previousRound' => $roundData['previousRound']
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
        $rounds = $this->promoConfig[EsPromoType::ESTUDYANTREPRENEUR]['option'];
        $roundData = $this->__getPreviousRounds();
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

    /**
     * Returns the standing
     * @param $round
     * @param $schoolAndStudents
     * @return array
     */
    public function getStandingsByRound($round = false, $schoolAndStudents = false)
    {
        $rounds = $this->promoConfig[EsPromoType::ESTUDYANTREPRENEUR]['option'];
        $roundData = $this->__getPreviousRounds();
        $currentRound = $round ? $rounds[$round] : $rounds[$roundData['round']];
        $schools = $this->em->getRepository('EasyShop\Entities\EsSchool')->getAllSchools();
        $schoolsAndStudents = $schoolAndStudents ?: $this->__getStudentsByDateAndSchool(
                                                              $schools,
                                                              $currentRound['start'],
                                                              $currentRound['end'],
                                                              $roundData['limit']
                                                          );
        $totalVotesPerSchool = $this->__getTotalVotesByDate($currentRound['start'], $currentRound['end']);

        foreach ($schoolsAndStudents as $school => $students) {

            foreach ($students['students'] as $key => $student) {

                if (isset($totalVotesPerSchool[$school])) {
                    $currentPercentage = ($student['vote'] / $totalVotesPerSchool[$school]) * 100;
                    $schoolsAndStudents[$school]['students'][$key]['currentPercentage'] = $currentPercentage;
                }

            }

        }

        return $schoolsAndStudents;
    }

}
