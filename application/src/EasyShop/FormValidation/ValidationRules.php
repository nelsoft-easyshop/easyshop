<?php

namespace EasyShop\FormValidation;

use Symfony\Component\Validator\Constraints as Assert;
use EasyShop\FormValidation\Constraints as CustomAssert;


/**
 * Validation Rules Class
 *
 * refer to: http://symfony.com/doc/current/reference/constraints.html
 * for the complete constraint list
 */
class ValidationRules
{

    /**
     * Rules holder
     * 
     * @var mixed
     */
    private $rules = [];
    
    /**
     * The entity manager
     *
     */
    private $em;
     
     
    /**
     * Constructor.
     */
    public function __construct($em)
    {   
        $this->em = $em;
        $this->initValidationRules();
    }

    /**
     * Populates $rules
     */
    public function initValidationRules()
    {
        $this->rules = array(
            'bug_report' => [
                    'title' => [
                            new Assert\NotNull(["message" => "This field is required."])
                    ],
                    'description' => [
                            new Assert\NotNull(["message" => "This field is required."])
                    ],
                    'image' => [
                            new Assert\Image([
                                'mimeTypes' => ['image/png','image/jpg','image/jpeg','image/gif'],
                                'mimeTypesMessage' => 'This file is not a valid image. Accepted extensions are .png, .jpg, .jpeg and .gif only.',
                                'maxSize' => '5M',
                                'uploadIniSizeErrorMessage' => 'The file is too large. Allowed maximum size is 5 MB.'
                                ])
                    ],
                    'captcha' => [
                            new Assert\NotNull(["message" => "This field is required."])
                    ],
             ],
            'login' => array(
                    'username' => array(
                                new Assert\NotBlank(),
                    ),
                    'password' => array(
                                new Assert\NotBlank(),
                    ),
                ),
            'register' => array(
                    'username' => array(
                                new Assert\NotBlank(),
                                new Assert\Length(['min' => '5', 
                                                   'max' => '25']),
                                new CustomAssert\ContainsAlphanumericUnderscore(),
                                new CustomAssert\IsUsernameUnique(),
                    ),
                    'password' => array(
                                new Assert\NotBlank(),
                                new Assert\Length(['min' => '6',]),
                                new CustomAssert\IsValidPassword(),
                    ),
                    'contactno' => array(
                                new Assert\Length(['min' => '11',
                                                     'max' => '11']),
                                new CustomAssert\IsValidMobile(),
                                new CustomAssert\IsMobileUnique(),
                    ),
                    'email' => array(
                                new Assert\NotBlank(),
                                new Assert\Email(),
                                new CustomAssert\IsEmailUnique(),
                    ),
                    'gender' => array(
                                new Assert\NotBlank(),
                                new CustomAssert\IsValidGender(),
                    ),
                ),
            'subscribe' => array(
                    'email' => array(
                                new Assert\Email(),
                                new Assert\NotBlank(),
                    ),
                ),
            'vendor_contact' => array(
                    'shop_name' => array(
                                new Assert\NotBlank(),
                                new Assert\Length(['min' => '5',
                                                   'max' => '60']),
                                new CustomAssert\IsAlphanumericSpace(),
                    ),
                    'contact_number' => array(
                                new CustomAssert\IsValidMobileOptional(),
                                new CustomAssert\IsMobileUnique(),
                    ), 
                    'street_address' => array(
                                new CustomAssert\IsValidAddressOptional(),
                    ),
                    'city' => array(
                                new Assert\NotBlank(),
                                new Assert\NotEqualTo(['value' => '0',
                                                       "message" => "Please select a city.",
                                                    ]),
                    ),
                    'region' => array(
                                new Assert\NotBlank(),
                                new Assert\NotEqualTo(['value' => '0',
                                                       "message" => "Please select a state region.",
                                                    ]),
                    ),
                ),
            'user_shipping_address' => array(
                    'consignee' => array(
                                new Assert\NotBlank(),
                                new CustomAssert\IsAlphanumericSpace(),
                    ),
                    'city' => array(
                                new Assert\NotBlank(),
                                new Assert\NotEqualTo(['value' => '0',
                                                       "message" => "Please select a city.",
                                                    ]),
                    ),
                    'region' => array(
                                new Assert\NotBlank(),
                                new Assert\NotEqualTo(['value' => '0',
                                                       "message" => "Please select a state region.",
                                                    ]),
                    ),
                    'mobile_number' => array(
                                new Assert\NotBlank(),
                                new CustomAssert\IsValidMobile(),
                    ),
                    'telephone_number' => array(
                                new CustomAssert\IsValidTelephone()
                    ),                   
                    'street_address' => array(
                                new Assert\NotBlank(),
                                new Assert\Length(['min' => '5',
                                                   'max' => '250']),
                    ),
            ),
            'personal_info' => array(
                    'dateofbirth' => array(
                                new Assert\Date(['message' => "Invalid Birthday format."])
                    ),
                    'email' => array(
                                new Assert\NotBlank(),
                                new Assert\Email()
                    ),
                    'mobile' => array(
                                new CustomAssert\IsValidMobile(),
                                new CustomAssert\IsMobileUnique(),
                    ),
                    'storeDescription' => array(
                                new Assert\Length(['max' => '1024'])
                    ),
                    'gender' => array(
                                new CustomAssert\IsValidGender(),
                    ),   
                    'shop_name' => array(
                                new Assert\NotBlank(),
                                new Assert\Length(['min' => '5',
                                                   'max' => '60']),
                                new CustomAssert\IsAlphanumericSpace(),
                    ),                 
            ),
            'store_setup' => [
                    'shop_name' =>  [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => '5',
                                           'max' => '60']),
                        new CustomAssert\IsAlphanumericSpace(),
                    ],
                    'shop_slug' => [
                        new Assert\NotBlank(),
                        new CustomAssert\ContainsAlphanumericUnderscore(),
                        new Assert\Length(['min' => '3',
                                            'max' => '25']),
                    ],
                    'category_name' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => '3',
                                            'max' => '50']),
                    ],
            ],
            'payment_account' => [
                    'account-bank-id' => [
                                new Assert\NotBlank(),
                                new Assert\NotEqualTo(['value' => '0',
                                                       "message" => "Please select a bank.",
                                                    ]),
                            ],
                    'account-name' => [
                                new Assert\NotBlank([ "message" => "The account name cannot be blank",]),
                            ],
                    'account-number' => [
                                new Assert\NotBlank([ "message" => "The account number cannot be blank",]),
                            ],
                    'account-id' => [
                                new Assert\NotBlank(),
                                new Assert\NotEqualTo(['value' => '0',
                                                       "message" => "This account is invalid.",
                                                    ]),
                    ],
            ],
            'reset_password' => [
                    'email' => [
                        new Assert\Email(),
                        new Assert\NotBlank(),
                    ],
                    'hash' => [
                        new Assert\NotBlank(),
                    ],
            ],
            'custom_category' => [
                'name' => [
                    new Assert\NotBlank([ "message" => "The category name cannot be blank",]),
                    new Assert\Length(['min' => '3',
                                       'max' => '255']),
                    new CustomAssert\isAlphanumericSpace(),
                ],
            ],
            'user_feedback' => [
                'message' => [
                    new Assert\NotBlank([ "message" => "The feedback cannot be empty",]),
                    new Assert\Length(['max' => '1024']),
                ],
                'rating' => [
                    new Assert\NotBlank([ "message" => "The feedback cannot be empty"]),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 5,
                    ]),
                ],
            ],
        );
    }

    /**
     * Returns rules for a specific form
     *
     * @param string $formName Name of the form
     *
     * @return mixed
     */
    public function getRules($formName)
    {
        return $this->rules[$formName];
    }
}
