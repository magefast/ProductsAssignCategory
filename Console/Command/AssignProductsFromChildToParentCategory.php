<?php
/**
 * @author magefast@gmail.com https://www.magefast.com
 */

namespace Dragonfly\ProductsAssignCategory\Console\Command;

use Dragonfly\ProductsAssignCategory\Service\AssignProductsBetweenCat;
use Dragonfly\ProductsAssignCategory\Service\ParentCategory;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AssignProductsFromChildToParentCategory extends Command
{
    public const VAR_NAME_CATEGORY = 'categoryId';

    private ParentCategory $parentCategory;
    private AssignProductsBetweenCat $assignProductsBetweenCat;

    /**
     * @param ParentCategory $parentCategory
     * @param AssignProductsBetweenCat $assignProductsBetweenCat
     * @param string|null $name
     */
    public function __construct(ParentCategory $parentCategory, AssignProductsBetweenCat $assignProductsBetweenCat, string $name = null)
    {
        parent::__construct($name);

        $this->parentCategory = $parentCategory;
        $this->assignProductsBetweenCat = $assignProductsBetweenCat;
    }

    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('category:assign:child_to_parent');
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
        $existCategoryId = $input->getOption(self::VAR_NAME_CATEGORY);

        if (empty($сategoryId)) {
            $parentCategoryId = $this->parentCategory->execute($existCategoryId);
            $this->assignProductsBetweenCat->execute($parentCategoryId, $existCategoryId);
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
