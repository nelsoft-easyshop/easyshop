<?php

class Estudyantrepreneur extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('usersession')) {
            redirect('/', 'refresh');
        }
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

        $this->load->spark('decorator');
        $this->load->view('templates/header',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/promo/estudyantrepreneur');
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
