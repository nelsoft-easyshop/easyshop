<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Widget extends MY_Controller
{
    const WIDGET_FIRST_TYPE = 1;
    const WIDGET_FIRST_TYPE_COUNT = 4;
    const WIDGET_SECOND_TYPE = 2;
    const WIDGET_SECOND_TYPE_COUNT = 6;
    const DEFAULT_CATEGORY_COUNT = 8;

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

    /**
     * Display widget selector page
     * @return view
     */
    public function widgets()
    {
        $viewData = [
            'firstWidgetLink' => base_url().'easywidget/1',
            'secondWidgetLink' => base_url().'easywidget/2',
        ];
        $this->load->view('pages/widgets/widget-selector', $viewData);
    }

    /**
     * Display first widget type
     * @return view
     */
    public function widget1()
    {
        $this->output->set_header('X-Frame-Options: GOFORIT');
        $this->generateWidget(self::WIDGET_FIRST_TYPE);
    }

    /**
     * Display second widget type
     * @return view
     */
    public function widget2()
    {
        $this->output->set_header('X-Frame-Options: GOFORIT');
        $this->generateWidget(self::WIDGET_SECOND_TYPE);
    }

    /**
     * Generate widget pages
     * @param  integer  $widgetType
     * @param  boolean  $asVar
     * @return view
     */
    private function generateWidget($widgetType, $asVar = false)
    {
        if ($widgetType === self::WIDGET_FIRST_TYPE) {
            $productCount = self::WIDGET_FIRST_TYPE_COUNT;
        }
        else {
            $productCount = self::WIDGET_SECOND_TYPE_COUNT;
        }

        $viewData['products'] = $this->xmlCms->getWidgetProducts($productCount);

        if ($widgetType === self::WIDGET_FIRST_TYPE) {
            $parentCategory = $this->em->getRepository('EasyShop\Entities\EsCat')
                                       ->getParentCategories(self::DEFAULT_CATEGORY_COUNT);
            $viewData['categories'] = $this->categoryManager
                                           ->applyProtectedCategory($parentCategory, false);
            return $this->load->view('pages/widgets/widget-1', $viewData, $asVar);
        }
        else {
            return $this->load->view('pages/widgets/widget-2', $viewData, $asVar);
        }
    }
}
