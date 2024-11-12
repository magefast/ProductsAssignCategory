<?php
/**
 * @author magefast@gmail.com https://www.magefast.com
 */

namespace Dragonfly\ProductsAssignCategory\Console\Command;

use Dragonfly\ProductsAssignCategory\Service\AssignProductsBetweenCat;
use Dragonfly\ProductsAssignCategory\Service\ChildCategoriesOfParent;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AssignProductsFromChildToParentCategoryByParentId extends Command
{
    public const VAR_NAME_CATEGORY = 'categoryId';

    private ChildCategoriesOfParent $childCategoriesOfParent;
    private AssignProductsBetweenCat $assignProductsBetweenCat;

    /**
     * @param ChildCategoriesOfParent $childCategoriesOfParent
     * @param AssignProductsBetweenCat $assignProductsBetweenCat
     * @param string|null $name
     */
    public function __construct(ChildCategoriesOfParent $childCategoriesOfParent, AssignProductsBetweenCat $assignProductsBetweenCat, string $name = null)
    {
        parent::__construct($name);

        $this->childCategoriesOfParent = $childCategoriesOfParent;
        $this->assignProductsBetweenCat = $assignProductsBetweenCat;
    }

    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('category:assign:to_parent');
        $this->setDescription('CLI Command for assign products from child category to parent category');
        $this->addOption(
            self::VAR_NAME_CATEGORY,
            null,
            InputOption::VALUE_REQUIRED,
            'Category ID'
        );
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $categoryId = $input->getOption(self::VAR_NAME_CATEGORY);

        if (empty($сategoryId)) {
            $childCategories = $this->childCategoriesOfParent->execute($categoryId);
            foreach ($childCategories as $childCategoryId) {
                $this->assignProductsBetweenCat->execute($categoryId, $childCategoryId);
            }
        } else {
            throw new RuntimeException('Missing required argument "' . self::VAR_NAME_CATEGORY . '"');
        }

        $reportSku = $this->assignProductsBetweenCat->getReportUpdatedSku();

        if (!empty($reportSku)) {
            $output->writeln('<info>UPDATED SKU:</info>');
            foreach ($reportSku as $sku) {
                $output->writeln('<info>' . $sku . '</info>');
            }
        }
    }
}
