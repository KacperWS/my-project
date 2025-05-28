<?php

namespace App\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\DataObject\Data\ImageGalleryItem;
use Pimcore\Model\DataObject\Data\Link;
use Pimcore\Model\DataObject;
use Pimcore\Model\Asset;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportProductsCommand extends AbstractCommand
{
    protected static $defaultName = 'app:import-products';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parent = DataObject::getByPath('/Products');
        $file = fopen(__DIR__ . '/../../var/import/products.csv', 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            $product = new Product();
            $product->setKey($data['sku']);
            $product->setSku($data['sku']);
            $product->setName($data['name']);
            $product->setParent($parent);
            $product->setDescription($data['description']);
            $product->setPrice((float)$data['price']);
            $product->setAvailability_status($data['availability_status']);
            $product->setManufacturer($data['manufacturer']);

            $images = new ImageGallery();
            foreach (explode('|', $data['images']) as $img) {
                $image = Asset::getByPath('/import/images/' . $img);
                if ($image) {
                    $item = new ImageGalleryItem();
                    $item->setImage($image);
                    
                    $images->addItem($item);
                }
            }
            $product->setImages($images);

            $docs = new Link();
            $docs->setText($data['technical_docs']);
            $product->setTechnical_docs($docs);

            $product->setPublished(true);
            //$output->writeln(" completed.");
            $product->save();
            //$output->writeln("Import .");
        }

        fclose($file);
        $output->writeln("Import completed.");
        return Command::SUCCESS;
    }
}
