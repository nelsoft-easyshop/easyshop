<?php

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->entityManager = $this->serviceContainer['entity_manager'];
        $this->transactionManager = $this->serviceContainer['transaction_manager'];
        $this->productManager = $this->serviceContainer['product_manager'];
    }

    public function index()
    {
        $pageInfo = array(
            'title' => 'Your Online Shopping Store in the Philippines | Easyshop.ph',
            'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
            'relCanonical' => base_url(),
        );
        $data = $this->fill_header();
        $data = array_merge($data, $pageInfo);
        if(!$this->session->userdata('member_id')){
            redirect('/', 'refresh');
        }
        if($data['logged_in']){
            $memberId = $this->session->userdata('member_id');
            $data['logged_in'] = true;
            $data['user_details'] = $this->entityManager->getRepository("EasyShop\Entities\EsMember")->find($memberId);
            $data['user_details']->profileImage = ltrim($this->serviceContainer['user_manager']->getUserImage($memberId, 'small'), '/');
        }
        $data['transactionInfo'] = $this->getMemberPageDetails();

        $this->load->view('templates/header_primary', $data);
        $this->load->view('pages/user/dashboard/dashboard-primary', $data);
        $this->load->view('templates/footer_primary');
    }

    public function getMemberPageDetails()
    {
        $memberId = $this->session->userdata('member_id');
        $data['transaction'] = [
            'ongoing' => [
                'bought' => $this->transactionManager->getBoughtTransactionDetails($memberId),
                'sold' => $this->transactionManager->getSoldTransactionDetails($memberId),
            ],
            'complete' => [
                'bought' => $this->transactionManager->getBoughtTransactionDetails($memberId),
                'sold' => '',
            ]
        ];

        return $data;
    }
}