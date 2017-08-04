<?php

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;


/**
 * Created by PhpStorm.
 * User: andre
 * Date: 4-8-2017
 * Time: 15:03
 */

class ReadCsvCommand extends ContainerAwareCommand
{
    // setup the structure of the command
    protected function configure()
    {
        $this->setName("readcsv")
             ->setDescription("Read csv and print data in console")
             ->addArgument('finder_in', InputArgument::REQUIRED, 'directory')
             ->addArgument('finder_name', InputArgument::REQUIRED, 'filename');
    }

    // execute the command
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $csvParsingOptions = array (
            'finder_in' =>  $input->getArgument('finder_in'),
            'finder_name' => $input->getArgument('finder_name'),
        );

        $csv = $this->parseCSV($csvParsingOptions);
            $table = new Table($output);
            $table->setRows($csv);
            $table->render();
    }

    // find the csv file and parse it into an array
    private function parseCSV($csvParsingOptions)
    {
        $finder = new Finder();
        $finder->files()
            ->in($csvParsingOptions['finder_in'])
            ->name($csvParsingOptions['finder_name']);

        foreach ($finder as $file) {
            $csv = $file;
        }

        if(empty($csv)){
            throw new \Exception("No csv file");
        }

        $rows = array();
        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;

    }
}