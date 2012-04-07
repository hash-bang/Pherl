Pherl - Perl like functionality for PHP
=======================================

This Spark module is intended to provide simple Perl like functionality for PHP users.

It contains a number of convenience functions cheerfully ripped from the Perl programming languages which make coding a lot easier for the terminally impatient.


Functions
=========

qw
--
Quickly initalize arrays by providing a single string. The array elements are determined by any whitespace.

	// Create an array with three elements (foo, bar and baz)
	$array = qw('foo bar baz');

	$array = qw('foo    bar    baz');

	$array = qw('
		foo
		bar
		baz
	');

TODO
====
* Regular expression functionality
