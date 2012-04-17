Pherl - Perl like functionality for PHP
=======================================

This Spark module is intended to provide simple Perl like functionality for PHP users.

It contains a number of convenience functions cheerfully ripped from the Perl programming languages which make coding a lot easier for the terminally impatient.

* __QW__ - Quick array initalization via simple strings
* __RE__ - Perl like regular expression syntax for PHP


Quick array initalizers - qw
============================
Quickly initalize arrays by providing a single string. The array elements are determined by any whitespace.

	// Create an array with three elements (foo, bar and baz)
	$array = qw('foo bar baz');

	$array = qw('foo    bar    baz');

	$array = qw('
		foo
		bar
		baz
	');


Regular expressions - re
========================
The Re() function provides all Regular Expression functionality in a Perl like way.


Simple matching
---------------
Determine if the string 'needle' exists in the variable $haystack:

	if (re('/needle/', $haystack)) {
		// Do something
	}


Simple extraction
-----------------

Extract a match from an input string

	$haystack = 'foo bar baz quz quuz';
	list($word) = re('/(qu.)/', $haystack);
	echo $word; // Output: 'quz'

RE can also return only the first captured element by using the '1' modifier. The following code will act the same as the above but force the only match into a string rather than an array:

	$haystack = 'foo bar baz quz quuz';
	$word = re('/(qu.)/1', $haystack);
	echo $word; // Output: 'quz'


Multiple extraction into an array
---------------------------------
Extract multiple matches into an array

	$haystack = 'foo bar baz quz quuz';
	$words = re('/(ba.)/', $haystack);
	print_r($words); // Output: array('bar', 'baz')

This is the same syntax as Simple Extraction. When multiple elements are found RE will return the elements as an array automatically.


Multiple extraction into variables
----------------------------------

You can use PHP's list() function to automatically cram the output of RE into a series of variables.

	$haystack = 'Matt is 28 years old';
	list($name, $age) = re('/^(.+) is ([0-9]+) years old$/', $haystack);


Simple substitution and replacement
--------------------------------
Substitution (also known as replacement) is also supported.

	$haystack = 'foo bar baz foo bar baz';
	$output = re('s/bar/BAR/', $haystack);
	echo $output; // Output: 'foo BAR baz foo bar baz'

By default only the first matching element is replaced. If you want to replace all matching items use the 'g' modifier:

	$haystack = 'foo bar baz foo bar baz';
	$output = re('s/bar/BAR/', $haystack);
	echo $output; // Output: 'foo BAR baz foo BAR baz'


Substitution back references
----------------------------
Replace the words 'bar' and 'baz' into 'FOUND-r' and 'FOUND-z':

	$haystack = 'foo bar baz';
	$output = re('s/(ba.)/FOUND-\1/', $haystack);
	echo $output; // Output: 'foo FOUND-r FOUND-z'

\1 and onwards is automatically set to the captured item.


Translation
-----------
Although not really used that much you can replace single characters based on a range:

	$haystack = 'foo bar baz';
	$output = re('tr/a-z/A-Z');
	echo $output; // Output: 'FOO BAR BAZ'


Perl to PHP reference
---------------------
This section contains some commonly used Perl syntax and the PHP equivelent when using this module.
This is included because sometimes examples are more helpful than API waffle.

<table>
	<tr>
		<th>Example</th>
		<th>Perl</th>
		<th>PHP + this module</th>
	</tr>
	<tr>
		<th>Extraction</th>
		<td>
			```perl
			$_ = 'foo bar baz';
			($one, $two, $three) =~ m/(.*) .{3} .../;
			```
		</td>
		<td>
			```php
			$haystack = 'foo bar baz';
			list($one, $two, $three) = re('m/(.*) .{3} .../', $haystack);
			```
		</td>
	</tr>
	<tr>
		<th>Subsitution</th>
		<td>
			```perl
			$_ = 'foo bar baz';
			$new = s/foo/QUZ/;
			```
		</td>
		<td>
			```php
			$haystack = 'foo bar baz';
			$new = re('s/foo/QUZ/', $haystack);
			```
		</td>
	</tr>
	<tr>
		<th>Translation</th>
		<td>
			```perl
			$_ = 'foo bar baz';
			$new = tr/a-z/A-Z/;
			```
		</td>
		<td>
			```php
			$haystack = 'foo bar baz';
			$new = re('tr/a-z/A-Z/', $haystack);
			```
		</td>
	</tr>
</table>


TODO
====

* Translation (tr//) not working correctly
