<?php

namespace TestTask\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;
use TestTask\Service\ApplicationParser;

class ApplicationParserTest extends TestCase
{


    public function testParse()
    {
	    $data = file_get_contents( __DIR__ . '/../src/Controller/apiResource/data.json');
	    $parser = new ApplicationParser();

	    try {
	    	$offer = $parser->parse($data);
	    } catch (Exception $e){
	    	echo $e->getMessage();
	    }

        $this->assertCount(3,$offer->getCountries());
        $this->assertEquals(77.34,$offer->getPayout());
        $this->assertEquals('iOS',$offer->getPlatform());
        $this->assertEquals('RU',$offer->getCountries()[0]);
    }

	public function testGetValues()
	{
		$data = file_get_contents( __DIR__ . '/../src/Controller/apiResource/data.json');

		$res = ApplicationParser::getValues(json_decode($data, true), []);
		print_r($res);
		$this->assertTrue(true,is_string($res['application_id']));
		$this->assertCount(3,$res['countries']);
		$this->assertEquals(10.00,$res['amount']);
		$this->assertEquals('iOS',$res['platform']);
		$this->assertEquals(3867,$res['points']);

	}
    
    

}
