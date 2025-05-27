<?php

namespace App\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\Asset;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportProductsCommand extends AbstractCommand
{
    protected static $defaultName = 'app:import-products';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = fopen(__DIR__ . '/../../var/import/products.csv', 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            $product = new Product();
            $product->setKey($data['sku']);
            $product->setSku($data['sku']);
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice((float)$data['price']);
            $product->setAvailabilityStatus($data['availability_status']);
            $product->setManufacturer($data['manufacturer']);

            $images = [];
            foreach (explode('|', $data['images']) as $img) {
                $image = Asset::getByPath('/import/images/' . $img);
                if ($image) {
                    $images[] = $image;
                }
            }
            $product->setImages($images);

            $docs = [];
            foreach (explode('|', $data['technical_docs']) as $doc) {
                $document = Asset::getByPath('/import/docs/' . $doc);
                if ($document) {
                    $docs[] = $document;
                }
            }
            $product->setTechnicalDocs($docs);

            $product->setPublished(true);
            $product->save();
        }

        fclose($file);
        $output->writeln("Import completed.");
        return Command::SUCCESS;
    }
}
