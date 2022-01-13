<?php

namespace Tretiak\ProductMenu\Block;

class ProductMenu extends \Magento\Framework\View\Element\Template
{
    protected $productCollectionFactory;
    protected $categoryFactory;
    protected $session;
    protected $currentCategoryId;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\Session $session
     * @param array $data
     */
    public function __construct(\Magento\Framework\View\Element\Template\Context               $context,
                                \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
                                \Magento\Catalog\Model\CategoryFactory                         $categoryFactory,
                                \Magento\Catalog\Model\Session                                 $session,
                                array                                                          $data = [])
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->session = $session;

        $this->getCurrentCategoryId();

        parent::__construct($context, $data);
    }

    /**
     * Get 3 random products per page
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProducts()
    {
        $collection = $this->productCollectionFactory->create();

        $collection->addAttributeToSelect('*');

        $collection->addAttributeToFilter('size', ['null' => true]);
        $collection->addAttributeToFilter('color', ['null' => true]);

        $category = $this->categoryFactory->create()->load($this->currentCategoryId);
        $collection->addCategoryFilter($category);

        $collection->getSelect()->orderRand();
        $collection->getSelect()->limit(3);

        return $collection;
    }

    /**
     * @return void
     */
    protected function getCurrentCategoryId()
    {
        $this->currentCategoryId = $this->session->getData('last_viewed_category_id');
    }
}
