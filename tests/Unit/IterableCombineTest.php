<?php

declare( strict_types = 1 );

namespace WMDE\IterableFunction\Tests\Unit;

use IteratorAggregate;
use PHPUnit\Framework\TestCase;

/**
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class IterableCombineTest extends TestCase {

	public function testTakesDifferentIterableTypes() {
		$combinedValues = iterable_merge(
			[ 1, 2 ],
			new \ArrayIterator( [ 3, 4 ] ),
			$this->newGeneratorAggregate( [ 5, 6 ] )
		);

		$this->assertCount( 6, $combinedValues );
	}

	private function newGeneratorAggregate( iterable $values ): \IteratorAggregate {
		return new class( $values ) implements IteratorAggregate {
			private $values;
			public function __construct( iterable $values ) {
				$this->values = $values;
			}
			public function getIterator() {
				yield from $this->values;
			}
		};
	}

	public function testNumericKeysDoNotOverrideEachOther() {
		$combinedValues = iterable_merge(
			[ 1, 2 ],
			[ 3, 4 ]
		);

		$this->assertSame( [ 1, 2, 3, 4 ], iterator_to_array( $combinedValues ) );
	}

	public function testStringKeysNotOverrideEachOther() {
		$combinedValues = iterable_merge(
			[ 'key' => 1 ],
			[ 'key' => 2 ],
			[ 'such' => 3 ]
		);

		$this->assertSame( [ 'key' => 2, 'such' => 3 ], iterator_to_array( $combinedValues ) );
	}

}
