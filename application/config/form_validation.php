<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(
            'login_form' => array(
                    array(
                        'field' => 'login_username',
                        'label' => 'Username',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'login_password',
                        'label' => 'Password',
                        'rules' => 'required'
                    )
                ),
            'personal_profile_address' => array(
                    array(
                        'field' => 'stateregion',
                        'label' => 'State/Region',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'city',
                        'label' => 'City',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'address',
                        'label' => 'Full Address',
                        'rules' => 'required'
                    )
                ),
            'add_feedback_transaction' => array(
                    array(
                        'field' => 'feedback-field',
                        'label' => 'Feedback Field',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'rating1',
                        'label' => 'Rating 1',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'rating2',
                        'label' => 'Rating 2',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'rating3',
                        'label' => 'Rating 3',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'order_id',
                        'label' => 'Order_ID',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'for_memberid',
                        'label' => 'Feedback for user',
                        'rules' => 'trim|required'
                    ),
                ),
            'billing_info' => array(
                    array(
                        'field' => 'bi_acct_name',
                        'label' => 'Bank Account Name',
                        'rules' => 'required'
                    ),					
                    array(
                        'field' => 'bi_acct_no',
                        'label' => 'Bank Account Number',
                        'rules' => 'required|numeric|max_length[18]'
                    )					
                ),
            'addShippingComment' => array(
                    array(
                        'field' => 'courier',
                        'label' => 'Courier',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'delivery_date',
                        'label' => 'Delivery Date',
                        'rules' => 'trim|required'
                    ),
                ),
            'bankdeposit' => array(
                    array(
                        'field' => 'bank',
                        'label' => 'Bank',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'ref_num',
                        'label' => 'Reference Number',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'amount',
                        'label' => 'Amount',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'date',
                        'label' => 'Date',
                        'rules' => 'trim|required'
                    ),
            ),
        );

    
/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */

