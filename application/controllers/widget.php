<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use EasyShop\Entities\EsCat as EsCat;

class Widget extends MY_Controller
{
    const WIDGET_FIRST_TYPE = 1;
    const WIDGET_FIRST_TYPE_COUNT = 4;
    const WIDGET_SECOND_TYPE = 2;
    const WIDGET_SECOND_TYPE_COUNT = 6;

    private $productManager;
    private $em;
    private $xmlCms;
    private $categoryManager;

    /**
     * Load class dependencies
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->productManager = $this->serviceContainer['product_manager'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->xmlCms = $this->serviceContainer['xml_cms'];
        $this->categoryManager = $this->serviceContainer['category_manager'];
    }

    public function widgets()
    {
        $viewData = [
            'firstWidgetLink' => base_url().'search-widget/1',
            'secondWidget' => base_url().'search-widget/2',
        ];
        $this->load->view('pages/widgets/widget-selector', $viewData);
    }

    public function widget1()
    {
        $this->generateWidget(self::WIDGET_FIRST_TYPE);
    }

    public function widget2()
    {
        $this->generateWidget(self::WIDGET_SECOND_TYPE);
    }

    private function generateWidget($widgetType, $asVar = false)
    {
        if($widgetType === self::WIDGET_FIRST_TYPE){
            $productCount = self::WIDGET_FIRST_TYPE_COUNT;
        }
        else{
            $productCount = self::WIDGET_SECOND_TYPE_COUNT;
        }

        $viewData['products'] = $this->xmlCms->getWidgetProducts($productCount);

        if($widgetType === self::WIDGET_FIRST_TYPE){
            $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                       ->findBy([
                                            'parent' => EsCat::ROOT_CATEGORY_ID
                                        ]);
            $viewData['categories'] = $this->categoryManager
                                           ->applyProtectedCategory($parentCategory, false);
            return $this->load->view('pages/widgets/widget-1', $viewData, $asVar);
        }
        else{
            return $this->load->view('pages/widgets/widget-2', $viewData, $asVar);
        }
    }
}
