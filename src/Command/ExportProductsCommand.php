<?php

namespace App\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject\Product;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportProductsCommand extends AbstractCommand
{
    protected static $defaultName = 'app:export-products';

    protected function configure()
    {
        $this->addOption('status', null, InputArgument::OPTIONAL, 'Filter by availiability');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $status = $input->getOption('status');
        $list = new Product\Listing();

        if ($status) {
            $list->addConditionParam('availabilityStatus = ?', $status);
        }

        $products = [];

        foreach ($list as $product) {
            $products[] = [
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'status' => $product->getAvailability_status(),
                'manufacturer' => $product->getManufacturer()
            ];
        }

        file_put_contents(__DIR__ . '/../../var/export/products.json', json_encode($products, JSON_PRETTY_PRINT));
        $output->writeln("Success export.");
        return Command::SUCCESS;
    }
}
