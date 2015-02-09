<?php

namespace EasyShop\Promo;

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
     * Get School and its student by date / round
     * @return array
     */
    public function getSchoolWithStudentsByRound()
    {
        $rounds = $this->promoConfig[7]['option'];
        $date = new \DateTime;
        $dateToday = $date->getTimestamp();
        $round = false;
        $previousStartDate = '';
        $previousEndDate = '';
        $result = [];
        $previousRound = '';
        $round = false;
        $case = '';
        $qb = $this->em->createQueryBuilder();

        foreach ($rounds as $key => $data) {
            $startDate = strtotime($data['start']);
            $endDate = strtotime($data['end']);
            if ($dateToday >= $startDate && $dateToday < $endDate) {
                $round = $key;
                $case = $round === 'first_round' ?:'second_round_and_inter_school';
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

        switch($case) {
            case 'first_round' :
                $students = $this->em->getRepository('EasyShop\Entities\EsStudent')->getAllStudents();

                foreach ($students as $student) {
                    if (!isset($result[$student['school']])) {
                        $result[$student['school']] = [];
                    }
                    $result[$student['school']][] = $student;
                }

                break;
            case 'second_round_and_inter_school' :
                $schools = $this->em->getRepository('EasyShop\Entities\EsSchool')->getAllSchools();
                foreach ($schools as $school) {
                    $schoolName = $school['name'];
                    $students = $this->em->getRepository('EasyShop\Entities\EsStudent')
                                         ->getStudentsByDateAndSchool(
                                             $previousStartDate,
                                             $previousEndDate,
                                             $school,
                                             $limit
                                         );

                    $result[$schoolName] = $students;

                    if ($students) {
                        end($result[$schoolName]);
                        $lastKey = key($result[$schoolName]);
                        $studentsWithSameVote = $this->em->getRepository('EasyShop\Entities\EsStudent')
                                                         ->getStudentsByDateAndSchool(
                                                             $previousStartDate,
                                                             $previousEndDate,
                                                             $school,
                                                             PHP_INT_MAX,
                                                             $result[$schoolName][$lastKey]['vote'],
                                                             $result[$schoolName][$lastKey]['student']
                                                         );

                        if ($studentsWithSameVote) {
                            $result[$schoolName] = array_merge($result[$schoolName], $studentsWithSameVote);
                        }
                    }
                }

                break;
            default :
                break;
        }

        $result = [
            'schools_and_students' => $result,
            'round' => $round
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
        $promo->setProductId(0);
        $promo->setCode(0);
        $promo->setStudentId($studentId);
        $promo->setPromoType(EsPromoType::ESTUDYANTREPRENEUR);
        $promo->setCreatedAt(new \DateTime('now'));
        $this->em->persist($promo);
        $this->em->flush();

        return $promo;
    }

}
