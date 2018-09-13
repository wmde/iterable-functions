<?php

declare( strict_types = 1 );

namespace WMDE\IterableFunction\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class IterableToArrayTest extends TestCase {

	/**
	 * @dataProvider arrayProvider
	 */
	public function testGivenArray_itIsReturnedAsIs( array $array ) {
		$this->assertSame( $array, iterable_to_array( $array ) );
	}

	public function arrayProvider() {
		return [
			[ [] ],
			[ [ 42 ] ],
			[ [ null, null, null ] ],
			[ [ [], (object)[], [], (object)[] ] ],
			[ [ 'foo' => 100, 'bar' => 200, 'baz' => 300 ] ],
			[ [ 10 => 'foo', 20 => 'bar', 30 => 'baz' ] ],
			[ [ 2 => 'foo', 'bar', 3 => 'baz' ] ],
		];
	}

	/**
	 * @dataProvider traversableProvider
	 */
	public function testGivenTraversable_itIsReturnedAsArray( array $expected, \Traversable $traversable ) {
		$this->assertSame( $expected, iterable_to_array( $traversable ) );
	}

	public function traversableProvider() {
		return [
			'empty iterator' => [
				[],
				new \ArrayIterator()
			],
			'normal iterator' => [
				[ 'a', 'b', 'c' ],
				new \ArrayIterator( [ 'a', 'b', 'c' ] )
			],
			'iterator with keys' => [
				[ 'a' => 10, 'b' => 20, 'c' => 30 ],
				new \ArrayIterator( [ 'a' => 10, 'b' => 20, 'c' => 30 ] )
			],
			'iterator with some explicit keys' => [
				[ 3 => null, 'a' => 10, 20, 'c' => 30 ],
				new \ArrayIterator( [ 3 => null, 'a' => 10, 20, 'c' => 30 ] )
			],
			'Traversable instance / IteratorAggregate' => [
				[ 'a' => 10, 'b' => 20, 'c' => 30 ],
				new class() implements \IteratorAggregate {
					public function getIterator() {
						return new \ArrayIterator( [ 'a' => 10, 'b' => 20, 'c' => 30 ] );
					}
				}
			],
			'Generator instance' => [
				[ 'a' => 10, 'b' => 20, 'c' => 30 ],
				( function() {
					yield 'a' => 10;
					yield 'b' => 20;
					yield 'c' => 30;
				} )()
			]
		];
	}

}
