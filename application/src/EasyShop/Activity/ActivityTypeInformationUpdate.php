<?php

namespace EasyShop\Activity;

class ActivityTypeInformationUpdate extends AbstractActivityType
{

    /**
     * User Manager
     *
     * @var EasyShop\User\UserManager
     */
    private $userManager;

    /**
     * Constructor
     *
     * @param EasyShop\User\UserManager $userManager
     */
    public function __construct($userManager)
    {
        parent::__construct();
        $this->userManager = $userManager;
    }

    /**
     * Action constant for product update 
     *
     * @var integer
     */
    const ACTION_INFORMATION_UPDATE = 0;

    /**
     * Action constant for product update 
     *
     * @var integer
     */
    const ACTION_AVATAR_UPDATE = 1;

    /**
     * Action constant for product update 
     *
     * @var integer
     */
    const ACTION_BANNER_UPDATE = 2;

    private $fieldDefinition = [
        'storeName' => 'Store Name',
        'password' => 'Password',
        'contactno' => 'Contact Number',
        'isEmailVerify' => 'Email Verification',
        'gender' => 'Gender',
        'email' => 'Email Address',
        'birthday' => 'Birthday',
        'fullname' => 'Full Name',
        'storeDesc' => 'Store Description',
        'slug' => 'Slug',
        'website' => 'Website',
        'stateregion' => 'Region',
        'city' => 'City',
    ];

    /**
     * Return formatted data for specific activity
     *
     * @param string $jsonData
     * @return mixed
     */
    public function getFormattedData($jsonData)
    {
        $activityData = json_decode($jsonData, true);
        if($activityData['action'] === \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_AVATAR_UPDATE){
            $activityData['userImage'] = $this->userManager
                                              ->getUserImage($activityData['memberId'], 'small');
        }
        elseif ($activityData['action'] === \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_BANNER_UPDATE) {
            $activityData['userImage'] = $this->userManager
                                              ->getUserImage($activityData['memberId'], 'banner');
        }
        else{
            $displayContents = [];
            foreach ($activityData as $key => $value) {
                if(isset($this->fieldDefinition[$key])){
                    if(strtolower($key) === "password"){
                        $displayContents[] = "Password changed";
                    }
                    else{
                        if(strtolower($key) === "contactno"){
                            if(trim($value) === ""){
                                $displayContents[] = $this->fieldDefinition[$key] . ' : Removed';
                            }else{
                                $displayContents[] = $this->fieldDefinition[$key] . ' : 0' . $value;
                            }
                        }
                        else{
                            $displayValue = trim($value) === "" ? "Removed" : $value;
                            $displayContents[] = $this->fieldDefinition[$key] . ' : ' . $displayValue;
                        }
                    }
                }
            }
            $activityData['contents'] = $displayContents;
        }

        return $activityData;
    }
}


