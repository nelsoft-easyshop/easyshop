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
			'register_form1' => array(
				   array(
						 'field'   => 'username',
						 'label'   => 'Username',
						 'rules'   => 'trim|required||min_length[5]|max_length[25]|callback_external_callbacks[register_model,validate_username]|callback_external_callbacks[register_model,alphanumeric_underscore]'
					  ),
				   array(
						 'field'   => 'password',
						 'label'   => 'Password',
						 'rules'   => 'trim|required|matches[cpassword]|min_length[6]|callback_external_callbacks[register_model,validate_password]'
					  ),
				   array(
						 'field'   => 'cpassword',
						 'label'   => 'Password Confirmation',
						 'rules'   => 'trim|required'
					  ),
				   array(
						'field' => 'email',
						'label' => 'Email Address',
						'rules' => 'trim|required|valid_email|callback_external_callbacks[register_model,validate_email]'
					  ),
				   /*array(
						 'field'   => 'captcha_word',
						 'label'   => 'User Captcha',
						 'rules'   => 'trim|required|callback_external_callbacks[register_model,validate_captcha]'
					  ),*/
				   array(
						 'field'   => 'terms_checkbox',
						 'label'   => 'Terms and Conditions',
						 'rules'   => 'required'
					),
				),
			/*'register_form2_a' => array(
				   array(
						 'field'   => 'register_mobile',
						 'label'   => 'Mobile number',
						 'rules' => 'trim|numeric|max_length[11]|matches[cregister_mobile]|callback_external_callbacks[register_model,checkifrequired,register_email]'
					  ),
				   array(
						 'field' => 'cregister_mobile',
						 'label' => 'Mobile Confirmation',
						 'rules' => 'trim|matches[register_mobile]'
					  ),
				   array(
						'field' => 'register_email',
						'label' => 'Email Address',
						'rules' => 'trim|valid_email|matches[cregister_email]|callback_external_callbacks[register_model,checkifrequired,register_mobile]'
				   ),
				   array(
						 'field' => 'cregister_email',
						 'label' => 'Email Confirmation',
						 'rules' => 'trim|matches[register_email]'
					  ),
				   array(
						 'field'   => 'terms_checkbox',
						 'label'   => 'Terms and Conditions',
						 'rules'   => 'required'
					),
				),
			'register_form2_b' => array(
				   array(
						 'field'   => 'verification_code',
						 'label'   => 'Verification Code',
						 'rules'   => 'trim|required'
					  ),
				),*/
			'personal_profile_main' => array(
					array(
						'field' => 'dateofbirth',
						'label' => 'Date of Birth',
						'rules' => 'callback_external_callbacks[memberpage_model,is_validdate]'
					),
					array(
						'field' => 'mobile',
						'label' => 'Mobile',
						'rules' => 'trim|numeric|min_length[10]|max_length[10]|callback_external_callbacks[register_model,is_validmobile]'
					),
					array(
						'field' => 'email',
						'label' => 'Email',
						'rules' => 'trim|min_length[6]|valid_email|required'
					),
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
			'personal_profile_school' => array(
					array(
						'field' => 'schoolname1',
						'label' => 'School Name',
						'rules' => 'required'
					),
					array(
						'field' => 'schoolyear1',
						'label' => 'School Year',
						'rules' => 'required|is_numeric'
					),
					array(
						'field' => 'schoollevel1',
						'label' => 'School level',
						'rules' => 'callback_external_callbacks[memberpage_model,select_set,0]'
					)
				),
			'personal_profile_work' => array(
					array(
						'field' => 'companyname1',
						'label' => 'Company Name',
						'rules' => 'required'
					),
					array(
						'field' => 'designation1',
						'label' => 'Designation',
						'rules' => 'required'
					),
					array(
						'field' => 'year1',
						'label' => 'Year',
						'rules' => 'required|is_numeric'
					),
				),
			'c_deliver_address' => array(
					array(
						'field' => 'consignee',
						'label' => 'Consignee',
						'rules' => 'required'
					),
					array(
						'field' => 'c_mobile',
						'label' => 'Mobile Number',
						'rules' => 'required|trim|numeric|min_length[10]|max_length[10]|callback_external_callbacks[register_model,is_validmobile]'
					),
					array(
						'field' => 'c_telephone',
						'label' => 'Telephone Number',
						'rules' => 'is_numeric'
					),
					array(
						'field' => 'c_stateregion',
						'label' => 'State/Region',
						'rules' => 'required'
					),
					array(
						'field' => 'c_city',
						'label' => 'City',
						'rules' => 'required'
					),
					array(
						'field' => 'c_address',
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
			'review_form' => array(
					array(
						'field' => 'subject',
						'label' => 'Subject',
						'rules' => 'required'
					),
					array(
						'field' => 'comment',
						'label' => 'Comment',
						'rules' => 'required'
					)
				),
			'changepass' => array(
				   array(
						 'field'   => 'cur_password',
						 'label'   => 'Current Password',
						 'rules'   => 'trim|required|min_length[6]|max_length[25]'					  
					  ),
				   array(
						 'field'   => 'password',
						 'label'   => 'Password',
						 'rules'   => 'trim|required|matches[cpassword]|min_length[6]|max_length[25]|callback_external_callbacks[register_model,validate_password]'
					  ),
				   array(
						 'field'   => 'cpassword',
						 'label'   => 'Password Confirmation',
						 'rules'   => 'trim|required'
					  )
				),
				
			'forgotpass' => array(
				   array(
						 'field'   => 'password',
						 'label'   => 'Password',
						 'rules'   => 'trim|required|matches[cpassword]|min_length[6]|max_length[25]|callback_external_callbacks[register_model,validate_password]'
					  ),
				   array(
						 'field'   => 'cpassword',
						 'label'   => 'Password Confirmation',
						 'rules'   => 'trim|required'
					  )

				),				
				
			'identify_form' => array(
					array(
						'field' => 'email',
						'label' => 'Email Address',
						'rules' => 'trim|required|valid_email|callback_external_callbacks[register_model,validate_email]'						
					  )
				),
			'landing_form' => array(
				   array(
						 'field'   => 'username',
						 'label'   => 'Username',
						 'rules'   => 'trim|required||min_length[5]|max_length[25]|callback_external_callbacks[register_model,validate_username]|callback_external_callbacks[register_model,alphanumeric_underscore]'
					  ),
				   array(
						 'field'   => 'password',
						 'label'   => 'Password',
						 'rules'   => 'trim|required|matches[cpassword]|min_length[6]|callback_external_callbacks[register_model,validate_password]'
					  ),
				   array(
						 'field'   => 'cpassword',
						 'label'   => 'Password Confirmation',
						 'rules'   => 'trim|required'
					  ),
				   array(
						'field' => 'email',
						'label' => 'Email Address',
						'rules' => 'trim|required|valid_email|callback_external_callbacks[register_model,validate_email]'
					  ),
				  array(
						'field' => 'mobile',
						'label' => 'Mobile',
						'rules' => 'trim|numeric|min_length[11]|max_length[11]|callback_external_callbacks[register_model,is_validmobile_register]|callback_external_callbacks[register_model,validate_mobile]'
					),
				),
			'subscription_form' => array(
					array(
						'field' => 'subscribe_email',
						'label' => 'Subscription Email',
						'rules' => 'trim|required|valid_email'
					)
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
					/*array(
						'field' => 'tracking_num',
						'label' => 'Tracking Number',
						'rules' => 'trim|required'
					),*/
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
						'rules' => 'trim|required|numeric'
					),
					array(
						'field' => 'date',
						'label' => 'Date',
						'rules' => 'trim|required'
					),
			)
		);

		
/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */

