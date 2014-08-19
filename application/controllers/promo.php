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
     * Renders page for post and win promo
     *
     * @return View
     */

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
        }else{
            echo json_encode(3);
        }
        #return 1 if account has promo = true (QUALIFIED)
        #return 2 if account has promo = false (PENDING)
        #return 3 if username doesnt exist (NOT-QUALIFIED)
    }

    public function scratchCardPromo()
    {
        $data = $this->fill_header();
        $data['title'] = 'Scratch to Win | Easyshop.ph';
        $data['metadescription'] = ''; //<-------------------------NOTE : Add description once banner recieved
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/easytreats', $banner_data = array(), TRUE);

        $this->load->view('templates/header', $data);
        $this->load->view('pages/promo/scratch_to_win', $viewData);
        $this->load->view('templates/footer');
    }

    public function validateScratchCardCode()
    {
        $this->load->config('protected_category', TRUE);
        $code = $this->config->item('protected_category');
        print "<pre>";
        print_r($code);
        print "</pre>";
        #waiting for mock up
    }
}
