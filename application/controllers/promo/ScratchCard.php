<?PHP

class ScratchCard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('htmlpurifier');
        $this->load->model("product_model");
        $this->load->library('session');
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
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/easytreats', $banner_data = array(), TRUE);

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
            redirect(base_url().'login', 'refresh');
        }
        $data = $this->fill_header();
        $data['title'] = 'Scratch to Win | Easyshop.ph';
        $data['metadescription'] = 'Scratch-to-win-promo';
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/easytreats', $banner_data = array(), TRUE);
        $viewData['product'] = $this->product_model->validateCode($this->input->get('code'));

        $this->load->view('templates/header', $data);
        $this->load->view('pages/promo/scratch_to_win', $viewData);
        $this->load->view('templates/footer');
    }

}
<<<<<<< HEAD

/* End of file ScratchCard.php */
/* Location: ./application/controllers/promo/ScratchCard.php */

=======
>>>>>>> issue-269
