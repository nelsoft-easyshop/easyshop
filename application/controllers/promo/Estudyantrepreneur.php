<?php

class Estudyantrepreneur extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->em = $this->serviceContainer['entity_manager'];
        $this->promoManager = $this->serviceContainer['promo_manager'];
    }

    /**
     * Retrieves the Estudyantrepreneur Promo Page
     */
    public function EstudyantrepreneurPromo()
    {
        $isLoggedIn = true;

        if (!$this->session->userdata('usersession')) {
            $isLoggedIn = false;
        }

        $data = $this->promoManager
                     ->callSubclassMethod(
                        \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR,
                        'getSchoolWithStudentsByRound'
                     );

        if ($data['showSuccessPage'] || $data['isPromoEnded']) {
            $this->EstudyantrepreneurPromoStandings();
        }
        else {
            $data['isLoggedIn'] = $isLoggedIn;
            $this->load->view('pages/promo/estudyantrepreneur', $data);
        }

    }

    /**
     * Retrieves Estudyantrepreneur Promo standings
     */
    public function EstudyantrepreneurPromoStandings()
    {
        $this->config->load('ipwhitelist');
        $ipwhitelist = $this->config->item('ip');
        $clientIp = $this->serviceContainer['http_request']
                         ->getClientIp();
        /**
         * Grant access to page only for office ips
         */
        if(in_array($clientIp, $ipwhitelist) === false){
            show_404(); 
            exit();
        }
    
        $data = $this->promoManager
                     ->callSubclassMethod(
                         \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR,
                         'getSchoolWithStudentsByRound'
                     );

        $getCurrentStandings['schools_and_students'] = $this->promoManager
                                                            ->callSubclassMethod(
                                                                \EasyShop\Entities\EsPromoType::ESTUDYANTREPRENEUR,
                                                                'getStandingsByRound'
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
        }

        $getCurrentStandings['successMessage'] = "Current Standings";

        $this->load->view('pages/promo/estudyantrepreneur_congrats', $getCurrentStandings);
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
            $result['errorMsg'] = 'Sorry, but you can only vote once. Please check back on the <a href="/Estudyantrepreneur#mechanics">mechanics</a> for more info.';
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
