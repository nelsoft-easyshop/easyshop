<?PHP
use Doctrine\ORM\Query\ResultSetMapping;
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
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/scratchAndWin', $banner_data = array(), TRUE);

        $this->load->view('templates/header', $data);
        $this->load->view('pages/promo/scratch_to_win', $viewData);
        $this->load->view('templates/footer');
    }

    /**
     * checks if the code exist
     *
     * @param code
     * @return json
     */
    public function validateScratchCardCode()
    {
        $result = $this->product_model->validateScratchCardCode($this->input->post('code'));
        $result['logged_in'] = true;
        if(!$this->session->userdata('usersession') && !$this->check_cookie()){
            $result['logged_in'] = false;
        }

        echo json_encode(!$result ? false : $result);
    }

    /**
     * Renders page for claiming item
     *
     * @return view
     */
    public function claimScratchCardPrize()
    {
        if (!$this->session->userdata('usersession') && !$this->check_cookie()) {
            redirect('/login', 'refresh');
        }
        if (!($this->input->get('code'))) {
            redirect('/Scratch-And-Win', 'refresh');
        }
        $data = $this->fill_header();
        $data['title'] = 'Scratch to Win | Easyshop.ph';
        $data['metadescription'] = 'Scratch-to-win-promo';
        $viewData['deals_banner'] = $this->load->view('templates/dealspage/easytreats', $banner_data = array(), TRUE);
        $viewData['product'] = $this->product_model->validateScratchCardCode($this->input->get('code'));
        $viewData['code'] = $this->input->get('code');
        if (!$viewData['product']) {
            redirect('/Scratch-And-Win', 'refresh');
        }
        else if (intval($viewData['product']['c_id_code']) !== 0) {
            $viewData['product'] = 'purchase-limit-error';
        }
        $slugs = array(
            'lg-optimus-g-pro-lite-black',
            'lg-nexus-5',
            'apple-iphone-5c-16gb-1',
            'lenovo-s820-red',
            'lenovo-a316i-android-42-4gb-black',
            'lg-optimus-l5-ii-e450-black'
        );
        $product = array();
        foreach($slugs as $slug){
            $dbProduct = $this->product_model->getProductBySlug($slug, false);
            $product[] = $dbProduct;
        }
        $viewData['gadgets_galore'] = $product;

        $this->load->view('templates/header', $data);
        $this->load->view('pages/promo/scratch_to_win', $viewData);
        $this->load->view('templates/footer');
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
     * ajax - update fullname
     * @param fullname
     * @return boolean
     */
    public function updateFullname()
    {
        $fullname = trim($this->input->post('fullname'));
        $memberId = $this->session->userdata('member_id');
        $em = $this->serviceContainer['entity_manager'];
        $member = $em->find('\EasyShop\Entities\EsMember', ['idMember'=>$memberId]);
        $member->setFullname($fullname);
        $em->persist($member);
        $em->flush();
        $result = FALSE;
        if($member->getFullname() === $fullname) {
            $result = true;
        }

        echo json_encode($result);
    }
}
