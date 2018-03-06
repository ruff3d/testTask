<?php

namespace TestTask\Command;

use GuzzleHttp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Client;
use TestTask\Service\ApplicationParser;

class TestOfferCommand extends Command
{

    protected static $defaultName = 'test:offer';
    private $em;
    private $client;
    private $parser;

	public function __construct(EntityManagerInterface $em, ApplicationParser $parser)
	{
		$this->parser = $parser;
		$this->em = $em;
		$this->client = new GuzzleHttp\Client(['headers' => [
				'User-Agent' => 'testing/1.0',
				'Accept'     => 'application/json',
			]]);
		parent::__construct();
	}

	protected function configure()
    {
        $this
            ->setDescription('Creates new Offer from response')
            ->addOption('url', null, InputOption::VALUE_OPTIONAL, 'Desired host')
        ;



    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

	    $url = $input->getOption('url') ?: 'http://localhost:8000';

	    $response = $this->client->get($url.'/api/data');

	    if ($response->getStatusCode() == 200){
	        	try {
	    	         $offer = $this->parser
			             ->setPrice(500)
			             ->parse($response->getBody());
	    	         $this->em->persist($offer);
	    	         $this->em->flush();
	    	         $io->success('You have created a new offer.');
	     	} catch (\Exception $exception) {
	        		$io->error( $exception->getMessage());
	        	}
	    }
    }

}
