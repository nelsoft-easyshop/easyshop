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

        if ($data['showSuccessPage'] || $data['isPromoEnded']) {
            $getCurrentStandings['schools_and_students'] = $this->promoManager
                                                                ->callSubclassMethod(
                                                                    \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR,
                                                                    'getStandingsByRound',
                                                                    [
                                                                        $data['previousRound'],
                                                                        $data['schools_and_students']
                                                                    ]
                                                                );

            if ($data['previousRound'] === 'first_round') {
                $getCurrentStandings['successMessage'] = "first round winners";
            }
            else if ($data['previousRound'] === 'inter_school_round'){
                $getCurrentStandings['successMessage'] = "Congratulations to the businesses who made it to the top 3. We wish you the best of luck at the inter-school poll!";
            }
            else if ($data['previousRound'] === 'inter_school_round'){
                $getCurrentStandings['successMessage'] = "success message for the winner";
            }

            $this->load->view('pages/promo/estudyantrepreneur_congrats', $getCurrentStandings);
        }
        else {
            $this->load->view('pages/promo/estudyantrepreneur', $data);
        }



    }

    /**
     * Retrieves success page
     */
    public function EstudyantrepreneurPromoSuccess()
    {
        if (!$this->input->post('studentId') || !$this->input->post('schoolName')) {
            redirect('/Estudyantrepreneur', 'refresh');
        }

        $studentId = (int) trim($this->input->post('studentId'));
        $memberId = $this->session->userdata('member_id');
        $data = $this->__vote($studentId, $memberId);
        $getCurrentStandings = $this->promoManager
                                    ->callSubclassMethod(
                                        \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR,
                                        'getStandingsByRound'
                                    );

        $bodyData = [
            'currentStandings' => $getCurrentStandings[$this->input->post('schoolName')],
            'result' => $data,
        ];

        $this->load->view('pages/promo/estudyantrepreneur_success', $bodyData);
    }

    /**
     * Vote for a student
     * @Param $studentId
     * @Return array
     */
    private function __vote($studentId, $memberId)
    {
        $studentId = (int) $studentId;
        $studentEntity = $this->em->find('EasyShop\Entities\EsStudent', $studentId);
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
            $result['errorMsg'] = 'Sorry, but you can only vote once per round. Please check back on the <a href="/Estudyantrepreneur#mechanics">mechanics</a> for the next round of voting.';
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
