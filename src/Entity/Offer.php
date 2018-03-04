<?php

namespace TestTask\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="TestTask\Repository\OfferRepository\Repository\OfferRepository")
 */
class Offer {
	/**
	 * @Assert\Uuid()
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	private $applicationId;

	/**
	 * @ORM\Column(type="array")
	 */
	private $countries;

	/**
	 * @ORM\Column(type="float")
	 */
	private $payout;

	/**
	 * @ORM\Column(type="string", columnDefinition="ENUM('Adnroid',
	 *                            'iOS')")
	 */
	private $platform;

	public function getApplicationId(): string {
		return $this->applicationId;
	}

	public function setApplicationId( string $applicationId ): Offer {
		$this->applicationId = $applicationId;

		return $this;
	}

	public function getCountries(): array {
		return $this->countries;
	}


	public function setCountries( array $countries ): Offer {
		foreach ( $countries as $country ) {
			$this->addCountry( $country );
		}

		return $this;
	}


	public function getPayout(): float {
		return $this->payout;
	}

	public function setPayout( float $payout ): Offer {
		$this->payout = $payout;

		return $this;
	}


	public function getPlatform(): string {
		return $this->platform;
	}

	public function setPlatform( $platform ): Offer {
		$this->platform = $platform;

		return $this;
	}

	/**
	 * @Assert\Country()
	 * @param string $country
	 *
	 * @return Offer
	 */
	public function addCountry( string $country ): Offer {
		$this->countries[] = $country;

		return $this;
	}

}
