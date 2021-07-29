<?php

declare( strict_types = 1 );

namespace WMDE\IterableFunction\Tests\Unit;

use IteratorAggregate;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class IterableCombineTest extends TestCase {

	/** @covers ::iterable_merge */
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

	/**
	 * @covers ::iterator_to_array
	 * @covers ::iterable_merge
	 */
	public function testNumericKeysDoNotOverrideEachOther() {
		$combinedValues = iterable_merge(
			[ 1, 2 ],
			[ 3, 4 ]
		);

		$this->assertSame( [ 1, 2, 3, 4 ], iterator_to_array( $combinedValues ) );
	}

	/**
	 * @covers ::iterator_to_array
	 * @covers ::iterable_merge
	 */
	public function testStringKeysNotOverrideEachOther() {
		$combinedValues = iterable_merge(
			[ 'key' => 1 ],
			[ 'key' => 2 ],
			[ 'such' => 3 ]
		);

		$this->assertSame( [ 'key' => 2, 'such' => 3 ], iterator_to_array( $combinedValues ) );
	}

	/**
	 * @dataProvider arrayInputsProvider
	 * @covers ::iterator_to_array
	 * @covers ::iterable_merge
	 */
	public function testEquivalencyWithArrayMergeAfterIteratorToArray( array ...$arrays ) {
		$this->assertSame(
			array_merge( ...$arrays ),
			iterator_to_array( iterable_merge( ...$arrays ) )
		);
	}

	public function arrayInputsProvider() {
		yield 'empty arrays' => [
			[],
			[]
		];

		yield 'numeric indexed arrays' => [
			[ 'a', 'b' ],
			[ 'c', 'd' ]
		];

		yield 'special numeric indexes' => [
			[ 10 => 'a', 'b' ],
			[ 'c', 20 => 'd' ]
		];

		yield 'string indexes mixed with numeric ones' => [
			[ 'foo' => 'a', 'b' ],
			[ 'c', 'bar' => 'd' ]
		];

		yield 'duplicate keys' => [
			[ 'foo' => 'a', 'b', 42 => 'x' ],
			[ 'c', 'foo' => 'd', 42 => 'y' ]
		];

		yield 'one array' => [
			[ 'c', 'foo' => 'd', 42 => 'y' ]
		];

		yield 'three arrays' => [
			[ 'such' ],
			[ 'foo' => 'bar' ],
			[ 42 ]
		];
	}

	/**
	 * @covers ::iterator_to_array
	 * @covers ::iterable_merge
	 */
	public function testNoArgumentsResultInEmptyGenerator() {
		$this->assertSame( [], iterator_to_array( iterable_merge() ) );
	}

}
