<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('qw')) {
/**
* Quickly initalize arrays by providing a single string. The array elements are determined by any whitespace.
* e.g.
* 	$a = qw('foo bar baz') // Output: array('foo', 'bar', 'baz')
* 	$a = qw('foo    bar    baz') // Same as above, as is any whitespace including line feeds
* @param string $string The string to split
* @return array The input $string variable split into an array by its whitespace
*/
function qw($string) {
	return preg_split('/\s+/', $string);
}
}

if (!function_exists('re')) {
/**
* A single function 're' which can run very complex RegExps
* First arg is the Perl compatible RegExp and the second is either a string or array of strings to match.
* e.g.
*
*	// Extract a match from a RegExp
*	$val = re('/(some match)dasdas/', $subject); // Sets $val to the matched str
*
*	// Do something only if a RegExp does NOT match
*	if (re('!/(some match)/', $subject)) {} // Returns boolean as to whether the match is present (quicker than above)
*
	// Substituion
*	$val = re('s/123/321/g', $subject) // S(ubstitute) all '123' with '321'
*
*	// Translation
*	$val = re('tr/a-z/A-Z/', $subject) // TR(anslate) upper-case to lower-case
*
*	list($name,$age,$height) = re('m/([a-z]+) is ([0-9]{1,3}) years old and is ([0-9\.]+) feet height',$instr); // Nice extraction
*
* Operators:
* * m (or nothing) - Returns all matching elements in a array (if multiple matches [i.e. \1\2\3 etc.] in an array of arrays)
* * s - Return the substution of the RegExp (or the changed array if $target is array)
* * tr - Inline translate (change each character to its corrisponding character)
* * ! - Boolean match return yes or no as to whether the subject is within the target (or in ANY of the targets if its an array)
*
* Flags:
* * RE_ALWAYSARRAY - Ignore the single return (when doing simple matches) and ALWAYS return an array-of-arrays
* * RE_FULLSTR - Include the full matching string as the first element of the matches
* * RE_CROP - When doing a match across an array should we remove all non-greping elements? (Default: Just return a blank array). Overrides RE_FULLSTR
*/
define('RE_ALWAYSARRAY',1);
define('RE_FULLSTR',2);
define('RE_CROP',4);
function re($exp, $target, $flags = 0) {
	preg_match_all('/^(m|s|tr|\!)?([^a-z0-9])/',$exp, $type, PREG_SET_ORDER); // Determine what type of operation to do & the split char
	$splitter = $type[0][2];
	$type = $type[0][1];
	$exp = ltrim($exp,$type); // Remove the leading operation character (PHP PREG lib doesnt like it)
	
	switch ($type) { // What exactly are we doing?
		case 's': // Substitution
			$splitterq = preg_quote($splitter,$splitter);
			preg_match_all("/^$splitterq(.*)$splitterq(.*)[$splitterq]$/i",$exp,$matches,PREG_SET_ORDER);
			if (is_array($target)) {
				$out = array();
				foreach ($target as $thistarget)
					$out[] = preg_replace("$splitter{$matches[0][1]}$splitter",$matches[0][2],$target);
				return $out;
			} else
				return preg_replace("$splitter{$matches[0][1]}$splitter",$matches[0][2],$target);
			print_r($matches);
			break;
		case 'tr': // Translation FIXME: Does this work with tr/a-z/A-Z/
			$splitter = preg_quote($splitter,$splitter);
			preg_match_all("/^$splitter(.*)$splitter(.*)[$splitter]$/i",$exp,$matches,PREG_SET_ORDER);
			if (is_array($target)) {
				$out = array();
				foreach ($target as $thistarget)
					$out[] = strtr($thistarget,$matches[0][1],$matches[0][2]);
				return $out;
			} else
				return strtr($target,$matches[0][1],$matches[0][2]);
			break;

		case '!': // Boolean match
			if (is_array($target)) {
				foreach ($target as $thistarget) {
					if (preg_match($exp,$thistarget) > 0)
						return true;
				}
				return false;
			} else
				return (preg_match($exp,$target) > 0);
			break;

		case 'm':
		default: // Match
			if (is_array($target)) {
				$out = array();
				foreach ($target as $thistarget) {
					preg_match_all($exp,$thistarget,$matches,PREG_SET_ORDER);
					if (($flags & RE_FULLSTR) == RE_FULLSTR) // Have to go though and put the thing in
						foreach ($matches as $index => $match)
							array_unshift($matches[$index],$thistarget);
					if (isset($matches[0]))
						$out[] = $matches[0];
					else if (($flags & RE_CROP) != RE_CROP) { // Nothing found in this element (and we're not in CROP mode)
						if (($flags & RE_FULLSTR) == RE_FULLSTR)
							$out[] = array($thistarget);
						else
							$out[] = array();
					}
				}
				return $out;
			} else {
				preg_match_all($exp,$target,$matches,PREG_SET_ORDER);
				if ((($flags & RE_ALWAYSARRAY) == RE_ALWAYSARRAY) || (count($matches) > 1)) { // If its multiple groups return the default array-of-arrays
					if (($flags & RE_FULLSTR) != RE_FULLSTR) // Have to go though and remove the damned thing
						foreach ($matches as $index => $match)
							array_shift($matches[$index]);
					return $matches;
				} else { // If its just a single match
					if (count($matches) > 0) {
						if (($flags & RE_FULLSTR) != RE_FULLSTR) // Clip the damned first element
							array_shift($matches[0]);
						return $matches[0]; // Just return the single match
					}
				}
			}
	}
}
}

// Test cases:
//print_r(re('m/([1-3])/','123456789123456789'));
//print_r(re('m/([1-3])/','123456789123456789',RE_ALWAYSARRAY));
//print_r(re('m/([1-3])[0-9]([1-3])/','123456789123456789'));
//print_r(re('/567/','123456789'));
//print_r(re('s/[5-9]/-R-/','123456789'));
//print_r(re('tr/1-9/a-z/','123456789'));
