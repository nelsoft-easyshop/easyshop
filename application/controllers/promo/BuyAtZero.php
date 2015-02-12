<?PHP

class BuyAtZero extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("product_model");
        $this->em = $this->serviceContainer['entity_manager'];
        $this->promoManager = $this->serviceContainer['promo_manager'];
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
        $productId = $this->input->post('id');

        if (!$this->session->userdata('member_id')) {
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')->find($productId);
            $this->session->set_userdata('uri_string', 'item/'.$product->getSlug());
            $data = 'not-logged-in';
        }
        else {
            $data = $this->em->getRepository('EasyShop\Entities\EsPromo')
                             ->registerMemberForBuyAtZero($productId, $this->session->userdata('member_id'));
        }

        echo json_encode($data);
    }

}


/* End of file BuyAtZero.php */
/* Location: ./application/controllers/promo/BuyAtZero.php */


