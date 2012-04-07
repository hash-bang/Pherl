<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('qw')) {
/**
* Quickly initalize arrays by providing a single string. The array elements are determined by any whitespace.
* e.g.
* 	$a = qw('foo bar baz') // Output: array('foo', 'bar', 'baz')
* 	$a = qw('foo    bar    baz') // Same as above, as is any whitespace including line feeds
*/
function qw($in) {
	return preg_split('/\s+/', $in);
}
}
