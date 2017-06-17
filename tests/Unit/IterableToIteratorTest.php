<?php

declare( strict_types = 1 );

namespace WMDE\IterableFunction\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class IterableToIteratorTest extends TestCase {

	/**
	 * @dataProvider arrayProvider
	 */
	public function testGivenArray_itIsReturnedAsIterator( array $array ) {
		$iterator = iterable_to_iterator( $array );

		$this->assertInstanceOf( \Iterator::class, $iterator );
		$this->assertSame( $array, iterator_to_array( $iterator ) );
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
	 * @dataProvider iteratorProvider
	 */
	public function testGivenIterable_itIsReturnedAsIs( \Traversable $traversable ) {
		$this->assertSame( $traversable, iterable_to_iterator( $traversable ) );
	}

	public function iteratorProvider() {
		return [
			'empty iterator' => [
				new \ArrayIterator()
			],
			'normal iterator' => [
				new \ArrayIterator( [ 'a', 'b', 'c' ] )
			],
			'iterator with keys' => [
				new \ArrayIterator( [ 'a' => 10, 'b' => 20, 'c' => 30 ] )
			],
			'iterator with some explicit keys' => [
				new \ArrayIterator( [ 3 => null, 'a' => 10, 20, 'c' => 30 ] )
			],
			'Generator instance' => [
				( function() {
					yield 'a' => 10;
					yield 'b' => 20;
					yield 'c' => 30;
				} )()
			]
		];
	}

	public function testGivenIteratorAggregate_iteratorIsReturned() {
		$traversable = new class() implements \IteratorAggregate {
			public function getIterator() {
				return new \ArrayIterator( [ 'a' => 10, 'b' => 20, 'c' => 30 ] );
			}
		};

		$iterator = iterable_to_iterator( $traversable );

		$this->assertInstanceOf( \Iterator::class, $iterator );
		$this->assertSame( [ 'a' => 10, 'b' => 20, 'c' => 30 ], iterator_to_array( $iterator ) );
	}

	public function testGivenIteratorAggregateWithGenerator_returnedIteratorIsRewindable() {
		$traversable = new class() implements \IteratorAggregate {
			public function getIterator() {
				yield 'a' => 10;
				yield 'b' => 20;
				yield 'c' => 30;
			}
		};

		$iterator = iterable_to_iterator( $traversable );

		$this->assertContainsOnly( 'int', $iterator );
		$this->assertContainsOnly( 'int', $iterator );
	}

	public function testGivenTraversable_iteratorIsReturned() {
		$traversable = new \DatePeriod(
			new \DateTime( '2012-08-01' ),
			new \DateInterval( 'P1D' ),
			new \DateTime( '2012-08-05' )
		);

		$iterator = iterable_to_iterator( $traversable );

		$this->assertInstanceOf( \Iterator::class, $iterator );
		$this->assertCount( 4, $iterator );
	}

	public function testGivenTraversable_returnedIteratorIsRewindable() {
		$traversable = new \DatePeriod(
			new \DateTime( '2012-08-01' ),
			new \DateInterval( 'P1D' ),
			new \DateTime( '2012-08-05' )
		);

		$iterator = iterable_to_iterator( $traversable );

		$this->assertContainsOnlyInstancesOf( \DateTime::class, $iterator );
		$this->assertContainsOnlyInstancesOf( \DateTime::class, $iterator );
	}

}
