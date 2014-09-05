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
<<<<<<< HEAD
        $productId = $this->input->post('id');
        if(!$this->session->userdata('member_id')){
            $slug = $this->product_model->getSlug($productId);
            $this->session->set_userdata('uri_string', 'item/'.$slug);
            $data = 'not-logged-in';
=======
        if(!$this->session->userdata('usersession') && !$this->check_cookie()){
            $data = 'Not logged in';
>>>>>>> issue-269
        }
        else{
            $data = $this->product_model->registerMemberForBuyAtZeroPromo(
                $this->input->post('id'),
                $this->session->userdata('member_id')
            );
        }
<<<<<<< HEAD
        
=======

>>>>>>> issue-269
        echo json_encode($data);
    }

}
<<<<<<< HEAD

/* End of file BuyAtZero.php */
/* Location: ./application/controllers/promo/BuyAtZero.php */

=======
>>>>>>> issue-269
