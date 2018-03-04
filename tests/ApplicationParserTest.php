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
    	    var_dump( $offer);
//        $this->assertCount(1,$offer);
    }

    public function provider(){
	    return  [file_get_contents( __DIR__ . '/../src/Controller/apiResource/data.json')];
    }
}
