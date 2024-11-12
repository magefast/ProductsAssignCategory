<?php
/**
 * @author magefast@gmail.com https://www.magefast.com
 */

namespace Dragonfly\ProductsAssignCategory\Service;

use Magento\Catalog\Model\CategoryFactory;

class ChildCategoriesOfParent
{
    public const SKIP_CATEGORY_IDS = [1, 2, 3];

    private CategoryFactory $categoryFactory;

    public function __construct(CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
    }

    public function execute(int $categoryId)
    {
        $categoriesArray = [];

        $category = $this->getCategory($categoryId);

        $subCategories = $category->getChildrenCategories();
        foreach ($subCategories as $subCategory) {
            if (in_array($subCategory->getId(), self::SKIP_CATEGORY_IDS)) {
                continue;
            }
            $categoriesArray[] = $subCategory->getId();
        }

        if (!empty($categoriesArray)) {
            return $categoriesArray;
        }

        return null;
    }

    private function getCategory($categoryId)
    {
        return $this->categoryFactory->create()->load($categoryId);
    }
}