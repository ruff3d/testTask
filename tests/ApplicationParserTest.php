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
//        $this->assertEquals(10,$offer->getPayout());
        $this->assertEquals('iOS',$offer->getPlatform());
    }

}
