<?PHP

class EasyDeals extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('htmlpurifier');
        $this->load->model("product_model");
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
        $promoCategoryId = $this->config->item('promo', 'protected_category');
        $this->load->library('xmlmap');
        
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Deals | Easyshop.ph',
            'metadescription' => 'Get the best price offers for the day at Easyshop.ph.'
        ];

        $banner_data = array();
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/easytreats', $banner_data, TRUE);        
        $parameters['category'] = $promoCategoryId;
        $parameters['page'] = 0;
        $parameters['limit'] = PHP_INT_MAX;
        $viewData['products'] = $this->serviceContainer['search_product']
                                     ->getProductBySearch($parameters)['collection'];        
        $this->load->spark('decorator');    
        $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/product/product_promo_category', $viewData);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
    }
 
}


/* End of file EasyDeals.php */
/* Location: ./application/controllers/promo/EasyDeals.php */


