<?php
/**
 * @author magefast@gmail.com https://www.magefast.com
 */

namespace Dragonfly\ProductsAssignCategory\Service;

use Magento\Catalog\Model\CategoryFactory;

class ParentCategory
{
    public const SKIP_PARENT_CATEGORY_IDS = [1, 2, 3];

    private CategoryFactory $categoryFactory;

    public function __construct(CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
    }

    public function execute(int $categoryId)
    {
        $parentCategoryId = null;

        $category = $this->getCategory($categoryId);

        $parentCategory = $category->getParentCategory();

        if ($parentCategory) {
            $parentCategoryId = $parentCategory->getId();
        }

        if (!empty($parentCategoryId) && !in_array($parentCategoryId, self::SKIP_PARENT_CATEGORY_IDS)) {
            return (int)$parentCategoryId;
        }

        return null;
    }

    private function getCategory($categoryId)
    {
        return $this->categoryFactory->create()->load($categoryId);
    }
}