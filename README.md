# Iterable Functions

[![Build Status](https://travis-ci.org/wmde/iterable-functions.svg?branch=master)](https://travis-ci.org/wmde/iterable-functions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wmde/iterable-functions/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wmde/iterable-functions/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wmde/iterable-functions/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wmde/iterable-functions/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wmde/iterable-functions/version.png)](https://packagist.org/packages/wmde/iterable-functions)
[![Download count](https://poser.pugx.org/wmde/iterable-functions/d/total.png)](https://packagist.org/packages/wmde/iterable-functions)

Provides basic functions to work with variables for type `iterable` (added in PHP 7.1).
Primarily to transform variables of type `iteratble`  into more specific types such as `array`.

If you have an iterable somewhere and you need to pass it to a function that only takes an `array`
or an `Iterator`, you have a problem. You will need to add conditional logic to find out the type
of the value and transform it if needed, which gets quite involved in the case of needing an `Iterator`.

This problem is quite common, as PHP's standard library, as of version 7.1, tends to either require
arrays, iterators or traversables.

Example:

```php
function doStuff(iterable $iterable) {
    $iterableMinusFooBar = array_diff( $iterable, [ 'foo', 'bar' ] );
}
// Output: array_diff(): Argument #1 is not an array
```

## Installation

To add this package as a local, per-project dependency to your project, simply add a
dependency on `wmde/iterable-functions` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
Iterable Functions 1.x:

```json
{
    "require": {
        "wmde/iterable-functions": "~1.0"
    }
}
```

## Usage

**When you need an array**

```php
function doStuff(iterable $iterable) {
    $iterableMinusFooBar = array_diff( iterable_to_array( $iterable ), [ 'foo', 'bar' ] );
}
```

**When you need an Iterator**

```php
function doStuff(iterable $iterable) {
    $firstFewThings = new LimitIterator( iterable_to_iterator( $iterable ), 42 );
}
```

## Running the tests

For a full CI run

	composer ci

For tests only

    composer test

For style checks only

	composer cs

## Release notes

### 0.2.0 (2018-09-13)

* Added `iterable_merge`

### 0.1.0 (2017-06-17)

Initial release with
 
* `iterable_to_array`
* `iterable_to_iterator`
