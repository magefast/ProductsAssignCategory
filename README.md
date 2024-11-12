# Dragonfly_ProductsAssignCategory module

This extension will helpful for working with products and categories of Magento Adobe Ecommerce.
<br>
<br>



## Added following CLI commands:

<br>

### Assign products form One category to Second category

    `php -d memory_limit=-1 bin/magento category:assign:child_to_parent --currentCategoryId 268 --newCategoryId 241`

Where `--currentCategoryId` - this is Category with products, 
Products of this category need assign to the New Category with param `--newCategoryId`.

<br>

### Assign product form Child Category with param `--categoryId` to Parent Category
    `php -d memory_limit=-1 bin/magento category:assign:child_to_parent --categoryId 268`

<br>

### Assign products from Child categories to Parent Category BY Parent Category `--categoryId`

    `php -d memory_limit=-1 bin/magento category:assign:to_parent --categoryId 241`
