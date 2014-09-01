<?PHP

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class promo extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('htmlpurifier');
        $this->load->model("product_model");
        $this->load->model("messages_model");
        $this->load->library('session');
    }

    /**
     * Renders view for the promo page
     *
     * @return View
     */
    public function category_promo()
    {
        $this->load->config('protected_category', TRUE);
        $categoryId = $this->config->item('promo', 'protected_category');
        $this->load->library('xmlmap');
        $data = $this->fill_header();
        $data['title'] = 'Deals | Easyshop.ph';
        $data['metadescription'] = 'Get the best price offers for the day at Easyshop.ph.';

        $banner_data = array();
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/easytreats', $banner_data, TRUE);
        #$viewData['items'] = $this->product_model->getProductsByCategory($categoryId,array(),0,"<",0,$this->per_page);
        $viewData['items'] = $this->product_model->getProductsByCategory($categoryId,array(),0,"<",0,PHP_INT_MAX);
        #PEAK HOUR PROMO ,To activate: change deals_banner = easydeals
        #$categoryId = $this->config->item('peak_hour_promo', 'protected_category');

        $this->load->view('templates/header', $data);
        $this->load->view('pages/product/product_promo_category', $viewData);
        $this->load->view('templates/footer');
    }

    /**
     * Checks the status of a particular user for the post and win promo
     *
     * @return JSON
     */
    public function PromoStatusCheck()
    {
        $this->load->model('user_model');
        $username = $this->input->post('username');
        $query_result = $this->user_model->getUserByUsername($username);
        if(isset($query_result['is_promo_valid'])){
            echo json_encode(intval($query_result['is_promo_valid']));
        }
        else{
            echo json_encode(3);
        }
    }

    /**
     * Promo page for scratch card promo
     *
     * @return array
     */
    public function scratchCardPromo()
    {
        $data = $this->fill_header();
        $data['title'] = 'Scratch to Win | Easyshop.ph';
        $data['metadescription'] = 'Scratch-to-win-promo';
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/scratchAndWin', $banner_data = array(), TRUE);

        $this->load->view('templates/header', $data);
        $this->load->view('pages/promo/scratch_to_win', $viewData);
        $this->load->view('templates/footer');
    }

    /**
     * checks if the code exist in DB
     *
     * @param code
     * @return json
     */
    public function validateScratchCardCode()
    {
        $result = $this->product_model->validateBuyAtZeroCode($this->input->post('code'));
        $result[0]['logged_in'] = true;
        if(!$this->session->userdata('usersession') && !$this->check_cookie()){
            $result[0]['logged_in'] = false;
        }

        echo json_encode(!$result[0] ? false : $result[0]);
    }

    /**
     * Renders page for claiming item
     *
     * @return view
     */
    public function claimScratchCardPrize()
    {
        if(!$this->session->userdata('usersession') && !$this->check_cookie()){
            redirect('/login', 'refresh');
        }
        $data = $this->fill_header();
        $data['title'] = 'Scratch to Win | Easyshop.ph';
        $data['metadescription'] = 'Scratch-to-win-promo';
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/easytreats', $banner_data = array(), TRUE);
        $viewData['product'] = $this->product_model->validateCode($this->input->get('code'));
        if($viewData['product'][0]){
            $this->load->view('templates/header', $data);
            $this->load->view('pages/promo/scratch_to_win', $viewData);
            $this->load->view('templates/footer');
        }
        else{
            redirect('/scratch-and-win', 'refresh');
        }
    }

    /**
     * ajax - tie up code to member
     *
     * @param memberId
     * @param code
     * @return boolean
     */
    public function tieUpMemberToCode()
    {
        $result = $this->product_model->tieUpMemberToCode(
            $this->session->userdata('member_id'),
            $this->input->post('code')
        );

        echo json_encode($result);
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
