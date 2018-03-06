<?php

namespace TestTask\Service;

use League\ISO3166\Exception\OutOfBoundsException;
use League\ISO3166\ISO3166;
use TestTask\Entity\Offer;

class ApplicationParser {
	/**
	 * Country converter
	 * @var ISO3166
	 */
	private $alpha;
	/**
	 * USD to Points
	 * @var int
	 */
	private $price = 500;

	/**
	 * @param int $price
	 *
	 * @return ApplicationParser
	 */
	public function setPrice( int $price ): ApplicationParser
	{
		$this->price = $price;
		return $this;
	}

	public function __construct()
	{
		$this->alpha = new ISO3166();
	}

	public function parse( $data ): Offer
	{
		$parsedData = json_decode( $data,true );
		if ( empty( $parsedData ) && !is_array( $parsedData ) ) {
			throw new \Exception( 'Wrong request data' );
		}

		$offer = self::getValues( $parsedData );

		if ( !isset( $offer['amount']) && !isset( $offer['points'] ) ) {
			throw new \Exception( 'Empty amount/points' );
		}

		$offer['payout'] = $offer['amount'] * $offer['points'] / $this->price;

		$offer['countries'] = array_map( function ( $country ) {
			try {
				$a2 = $this->alpha->alpha3( $country );
			} catch ( OutOfBoundsException $e ) {
				return '';
			}

			return $a2['alpha2'];
		}, $offer['countries'] );

		return ( new Offer() )
			->setApplicationId( $offer['application_id'] )
			->setCountries( $offer['countries'] )
			->setPayout( $offer['payout'] )
			->setPlatform( $offer['platform'] );
	}

	public static function getValues( $data ): array
	{
		static $offer;
		foreach ($data as $key => $value) {
			if ( is_array($value) && !is_string($key)) {
				self::getValues($value); continue;
			}
			switch ( $key ) {
				case 'uid'      :
					$offer['application_id'] = $value;
					break;
				case 'countries':
					$offer['countries'] = $value;
					break;
				case 'payout'   :
					$offer['amount'] = $value['amount'] ?? 0.0;
					break;
				case 'platform' :
					$offer['platform'] = $offer['platform'] ?? $value;
					break;
				case 'points'   :
					$offer['points'] = $value;
					break;
				default :
					continue;
			}
		}
		return $offer;
	}


}