<?php 

namespace EasyShop\Promo;

abstract class AbstractPromo
{


    
    /**
     * Flag if the promo has started
     * 
     * @var bool
     */
    protected $isStartPromo = false;
    
    /**
     * Flag if the promo has ended
     * 
     * @var bool
     */
    protected $isEndPromo = false;
    
    /**
     * The price after the promo calculation
     *
     * @var float
     */
    protected $promoPrice = 0.0000;
    
    /**
     * Product Entity
     *
     * @var EasyShop\Entities\Product 
     */
    protected $product;
    
    /**
     * Start Date of Promo
     *
     * @var DateTime
     */
    protected $startDateTime;
    
    /**
     * End Date of Promo
     *
     * @var DateTime
     */
    protected $endDateTime;
    
    /**
     * Current Date
     * 
     * @var DateTime
     */
    protected $dateToday;
    
      /**
     * Array of times in the day to trigger the sale
     * 
     * @var mixed
     */
    protected $option = array();

       
    /**
     * Abstract method for applying the promo logic
     * @return EasyShop\Entities\Product 
     *
     */
    abstract public function apply();


    /**
     * @param EasyShop\Entities\Product
     *
     */
    public function __construct(\EasyShop\Entities\EsProduct $product)
    {
        $this->product = $product;
        $this->startDateTime = $product->getStartDate();
        $this->endDateTime = $product->getEndDate();
        $this->dateToday = new \DateTime();
    }
        
    public function persist()
    {
        $this->product->setFinalPrice($this->promoPrice);
        $this->product->setStartPromo($this->isStartPromo);
        $this->product->setEndPromo($this->isEndPromo);
    }

    
    /**
     * Adds an option period to the array
     *
     * @param mixed $option
     */
    public function setOptions($option)
    {
        $this->option = $option;
    }

        
}

