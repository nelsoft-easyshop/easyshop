<?PHP

class BuyAtZero extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("product_model");
    }

    /**
     * Register user in buy at zero promo
     *
     * @param product_id
     * @param member_id
     * @return boolean
     */
    public function buyAtZeroRegistration()
    {
        $data = $this->product_model->registerMemberForBuyAtZeroPromo(
            $this->input->post('id'),
            $this->session->userdata('member_id')
        );

        echo json_encode($data);
    }

}

/* End of file BuyAtZero.php */
/* Location: ./application/controllers/promo/BuyAtZero.php */

