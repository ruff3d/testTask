<?php

namespace TestTask\Service;

use PHPUnit\Runner\Exception;
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
		$this->alpha = new \League\ISO3166\ISO3166;
	}

	public function parse( $data ): Offer {
		$parsedData = json_decode( $data );
		if ( empty( $parsedData ) && !is_array( $parsedData ) ) {
			throw new \Exception( 'Wrong request data' );
		}
		$offer = [];
		foreach ( $parsedData as $pdd ) {
			foreach ( $pdd as $pd ) {
				foreach ( $pd as $key => $value ) {
					switch ( $key ) {
						case 'uid'      :
							$offer['application_id'] = $value;
							break;
						case 'countries':
							$offer['countries'] = $value;
							break;
						case 'payout'   :
							$amount = ( is_array( $value ) && isset( $value['amount'] ) ) ? $value['amount'] : 0;
							break;
						case 'platform' :
							$offer['platform'] = $offer['platform'] ?? $value;
							break;
						case 'points'   :
							$points = $value;
							break;
					}
				}
			}
		}

		if ( !isset( $amount ) && !isset( $points ) ) {
			throw new \Exception( 'Empty amount/points' );
		}

		$offer['payout'] = $amount * $points * $this->price;

		$offer['countries'] = array_map( function ($country) {
			try {
				$a2 = $this->alpha->alpha3($country);
			} catch (\League\ISO3166\Exception\OutOfBoundsException $e) {
				return '';
			}
			return $a2['alpha3'];
		}, $offer['countries']);

		return (new Offer())
			->setApplicationId( $offer['application_id'])
			->setCountries( $offer['countries'])
			->setPayout( $offer['payout'])
			->setPlatform( $offer['platform']);
	}

}