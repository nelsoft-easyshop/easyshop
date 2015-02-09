<?php

class Estudyantrepreneur extends MY_Controller
{
    /**
     * Must be transferred to a config file, or not?
     * Declaration of Rounds, Start and End dates and Number of qualified per round
     * @var array
     */
    private $rounds = [
        'first_round' =>
            [
                'start' => '2015-02-23 00:00:00',
                'end' => '2015-03-06 23:59:59',
                'limit' => PHP_INT_MAX
            ],
        'second_round' =>
            [
                'start' => '2015-03-07 00:00:00',
                'end' => '2015-04-07 23:59:59',
                'limit' => 3
            ],
        'final_round' =>
            [
                'start' => '2015-06-08 00:00:00',
                'end' => '2015-07-08 23:59:59',
                'limit' => 1
            ],
    ];

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('usersession')) {
            redirect('/', 'refresh');
        }

        $this->em = $this->serviceContainer['entity_manager'];
        $this->promoManager = $this->serviceContainer['promo_manager'];
        $this->estudyantrepreneurManager = $this->serviceContainer['estudyantrepreneur_manager'];
    }

    /**
     * Retrieves the Estudyantrepreneur Promo Page
     */
    public function EstudyantrepreneurPromo()
    {
        $headerData = [
            'memberId' => $this->session->userdata('member_id'),
            'title' => 'Estudyantrepreneur | Easyshop.ph',
            'metadescription' => ''
        ];
        $data = $this->estudyantrepreneurManager->getSchoolWithStudentsByRound();
        $bodyData = [
            'schools_and_students' => $data['schools_and_students'],
            'round' => $data['round'],
        ];

        $this->load->spark('decorator');
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/promo/estudyantrepreneur', $bodyData);
        $this->load->view('templates/footer');
    }

    /**
     * Vote for a student
     * @Param studentId
     * @Param schoolId
     * @Return boolean
     */
    public function vote()
    {
        $studentId = (int) trim($this->input->post('studentId'));
        $studentEntity = $this->em->find('EasyShop\Entities\EsStudent', $studentId);
        $memberId = $this->session->userdata('member_id');
        $isUserAlreadyVoted = $this->em->getRepository('EasyShop\Entities\EsPromo')
                                       ->findBy([
                                           'memberId' => $memberId,
                                           'promoType' => \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR
                                       ]);
        $result = [
            'errorMsg' => 'Student does not exists',
            'isSuccessful' => false
        ];

        if ($isUserAlreadyVoted) {
            $result = [
                'errorMsg' => 'You have already voted'
            ];
        }
        elseif ($studentEntity) {
            $isVoteStudentSuccessful = $this->estudyantrepreneurManager
                                            ->voteStudent($studentEntity->getidStudent(), $memberId);
            if ($isVoteStudentSuccessful) {
                $result = [
                    'errorMsg' => '',
                    'isSuccessful' => true
                ];
            }
        }

        echo json_encode($result);
    }

}
