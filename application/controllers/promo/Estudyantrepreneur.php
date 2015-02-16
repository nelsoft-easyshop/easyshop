<?php

class Estudyantrepreneur extends MY_Controller
{

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
        $data = $this->promoManager
                     ->callSubclassMethod(
                        \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR,
                        'getSchoolWithStudentsByRound'
                     );
        $bodyData = [
            'schools_and_students' => $data['schools_and_students'],
            'round' => $data['round'],
        ];

        $this->load->spark('decorator');
        $this->load->view('pages/promo/estudyantrepreneur', $bodyData);
    }

    public function EstudyantrepreneurPromoSuccess()
    {
        if (!$this->input->post('studentId')) {
            redirect('/Estudyantrepreneur', 'refresh');
        }

        $studentId = (int) trim($this->input->post('studentId'));
        $data = $this->__vote($studentId);
        $bodyData = [
            'currentStandings' => 1,
            'result' => $data,
        ];

        $this->load->spark('decorator');
        $this->load->view('pages/promo/estudyantrepreneur_success', $bodyData);
    }

    /**
     * Vote for a student
     * @Param $studentId
     * @Return array
     */
    private function __vote($studentId)
    {
        $studentId = (int) $studentId;
        $studentEntity = $this->em->find('EasyShop\Entities\EsStudent', $studentId);
        $memberId = $this->session->userdata('member_id');
        $isUserAlreadyVoted = $this->promoManager
                                   ->callSubclassMethod(
                                       \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR,
                                       'isUserAlreadyVoted',
                                       [
                                           $memberId
                                       ]
                                   );
        $result = [
            'errorMsg' => 'Student does not exist',
            'isSuccessful' => false
        ];

        if ($isUserAlreadyVoted) {
            $result = [
                'errorMsg' => 'Sorry, but you can only vote once per round. Please check back on the <a href="/Estudyantrepreneur#mechanics">mechanics</a> for the next round of voting.'
            ];
        }
        elseif ($studentEntity) {
            $isVoteStudentSuccessful = $this->promoManager
                                            ->callSubclassMethod(
                                                \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR,
                                                'voteStudent',
                                                [
                                                    $studentEntity->getidStudent(),
                                                    $memberId
                                                ]);
            if ($isVoteStudentSuccessful) {
                $result = [
                    'errorMsg' => '',
                    'isSuccessful' => true
                ];
            }
        }

        return $result;
    }

}
