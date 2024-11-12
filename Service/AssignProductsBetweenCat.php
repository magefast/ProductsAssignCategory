<?php
/**
 * @author magefast@gmail.com https://www.magefast.com
 */

namespace Dragonfly\ProductsAssignCategory\Service;

use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Model\CategoryFactory;

class AssignProductsBetweenCat
{
    private array $updatedSku = [];
    private CategoryFactory $categoryFactory;
    private CategoryLinkManagementInterface $categoryLinkManagement;

    public function __construct(CategoryFactory $categoryFactory, CategoryLinkManagementInterface $categoryLinkManagement)
    {
        $this->categoryFactory = $categoryFactory;
        $this->categoryLinkManagement = $categoryLinkManagement;
    }

    public function execute(int $newCatId, int $existCatId)
    {
        $childCategoryProducts = $this->getProductCollection($existCatId);
        foreach ($childCategoryProducts as $product) {
            $categoryIds = [];
            foreach ($product->getCategoryIds() as $categoryId) {
                $categoryIds[$categoryId] = $categoryId;
            }

            if (!isset($categoryIds[$newCatId])) {
                $categoryIds[$newCatId] = $newCatId;
                $this->categoryLinkManagement->assignProductToCategories($product->getSku(), $categoryIds);

                $this->updatedSku[] = $product->getSku();
            }
        }
    }

    public function getProductCollection($categoryId)
    {
        $category = $this->getCategory($categoryId);
        return $category->getProductCollection()->addAttributeToSelect('*');
    }

    private function getCategory($categoryId)
    {
        return $this->categoryFactory->create()->load($categoryId);
    }

    public function getReportUpdatedSku()
    {
        return $this->updatedSku;
    }
}