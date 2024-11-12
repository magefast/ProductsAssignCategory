<?php
/**
 * @author magefast@gmail.com https://www.magefast.com
 */

namespace Dragonfly\ProductsAssignCategory\Console\Command;

use Dragonfly\ProductsAssignCategory\Service\AssignProductsBetweenCat;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AssignCategory extends Command
{
    public const VAR_NAME_CATEGORY_NEW = 'newCategoryId';
    public const VAR_NAME_CATEGORY_CURRENT = 'currentCategoryId';

    private AssignProductsBetweenCat $assignProductsBetweenCat;

    /**
     * @param AssignProductsBetweenCat $assignProductsBetweenCat
     * @param string|null $name
     */
    public function __construct(AssignProductsBetweenCat $assignProductsBetweenCat, string $name = null)
    {
        parent::__construct($name);

        $this->assignProductsBetweenCat = $assignProductsBetweenCat;
    }

    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('category:assign:current_to_new');
        $this->setDescription('CLI Command for assign products from one category to second category');
        $this->addOption(
            self::VAR_NAME_CATEGORY_NEW,
            null,
            InputOption::VALUE_REQUIRED,
            'Category ID for New Category'
        );
        $this->addOption(
            self::VAR_NAME_CATEGORY_CURRENT,
            null,
            InputOption::VALUE_REQUIRED,
            'Category ID of Current  Exist Category'
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
        $existCategory = $input->getOption(self::VAR_NAME_CATEGORY_CURRENT);
        $newCategory = $input->getOption(self::VAR_NAME_CATEGORY_NEW);

        if (empty($existCategory) || empty($newCategory)) {
            $this->assignProductsBetweenCat->execute($newCategory, $existCategory);
        } else {
            throw new RuntimeException('Missing required arguments "' . self::VAR_NAME_CATEGORY_CURRENT . ',' . self::VAR_NAME_CATEGORY_NEW . '"');
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
