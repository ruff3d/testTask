<?php

namespace TestTask\Service;

use League\ISO3166\Exception\OutOfBoundsException;
use League\ISO3166\ISO3166;
use TestTask\Entity\Offer;

class ApplicationParser {
	private $alpha;
	private $price = 500;

	/**
	 * @param int $price
	 */
	public function setPrice( int $price ): void {
		$this->price = $price;
	}

	public function __construct() {
		$this->alpha = new ISO3166();
	}

	public function parse( $data ): Offer {
		$parsedData = json_decode( $data,true );
		if ( empty( $parsedData ) && !is_array( $parsedData ) ) {
			throw new \Exception( 'Wrong request data' );
		}

		$offer = $this->getValues( $parsedData );

		if ( !isset( $amount ) && !isset( $points ) ) {
			throw new \Exception( 'Empty amount/points' );
		}

		$offer['payout'] = $amount * $points / $this->price;

		$offer['countries'] = array_map( function ( $country ) {
			try {
				$a2 = $this->alpha->alpha3( $country );
			} catch ( OutOfBoundsException $e ) {
				return '';
			}

			return $a2['alpha3'];
		}, $offer['countries'] );

		return ( new Offer() )
			->setApplicationId( $offer['application_id'] )
			->setCountries( $offer['countries'] )
			->setPayout( $offer['payout'] )
			->setPlatform( $offer['platform'] );
	}

	public function getValues( $data ) {
		static $offer;
		foreach ($data as $key => $value) {
			if ( is_array($value)) $this->getValues($value);
			switch ( $key ) {
				case 'uid'      :
					$offer['application_id'] = $value;
					break;
				case 'countries':
					$offer['countries'] = $value;
					break;
				case 'payout'   :
					$offer['amount'] = isset( $value['amount']  ) ? $value['amount'] : 0.0;
					break;
				case 'platform' :
					$offer['platform'] = $offer['platform'] ?? $value;
					break;
				case 'points'   :
					$offer['points'] = $value;
					break;
			}
		}
		return $offer;
	}

}