<?php

class Estudyantrepreneur extends MY_Controller
{
    /**
     * @var array
     */
    private $rounds = [
        'round_one' =>
            [
                '2015-02-23 00:00:00',
                '2015-03-06 23:59:59'
            ],
        'round_two' =>
            [
                '2015-03-07 00:00:00',
                '2015-04-07 23:59:59'
            ],
        'final_round' =>
            [
                '2015-06-08 00:00:00',
                '2015-07-08 23:59:59'
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
        $bodyData = [
            'schools_and_students' => $this->promoManager->getSchoolWithStudentsByRoundForEstudyantrepreneur($this->rounds)
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

    }

    /**
     * Retrieves the Current stats of students and Voting Success Page
     */
    public function votingSuccess()
    {

    }

}
