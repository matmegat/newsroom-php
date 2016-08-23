<?php

function value_or_null($value) 
{
	return $value ? $value : null;
}

function value_or($value, $alternative) 
{
	return $value ? $value : $alternative;
}

function value_if_test($test, $value, $else = null)
{
	if ($test) return $value;
	if ($else !== null) return $else;
	return null;
}

function str_ends_with($haystack, $needle)
{
	return substr($haystack, -strlen($needle)) == $needle;
}

function str_starts_with($haystack, $needle)
{
	return strpos($haystack, $needle) === 0;
}

function current_url()
{
	return $_SERVER['REQUEST_URI'];
}

// emulate a boolean full text search
// * [^a-z0-9\-] => used as wildcard
// @match the columns to match against
// @against the search terms as a string
function sql_search_terms($match, $against)
{
	if (!$against) return 1;
	if (!is_array($match)) $match = array($match);
	$raw_terms = explode(' ', $against);
	$sql_conds = array();
	
	// convert each term to sql like
	for ($i = 0, $c = count($raw_terms); $i < $c; $i++)
	{
		$bool = true;
		$term = trim($raw_terms[$i]);
		$cond = array();
		
		if (strlen($term) === 0) continue;
		
		// check for + or - at the start
		if (preg_match('#^[\+\-]#', $term))
		{
			$bool = $term[0] === '+';
			$term = substr($term, 1);
		}
		
		// convert any non-standard character to wildcard
		// * does not match the standard fulltext behaviour
		// * this also prevents sql injection
		$term = preg_replace('#[^a-z0-9\-]#i', '%', $term);
		
		// loop over each column in the match array
		for ($i2 = 0, $c2 = count($match); $i2 < $c2; $i2++)
			// generate the sql logic for one column
			$cond[] = $bool ? " {$match[$i2]} like '%{$term}%' ":
				" {$match[$i2]} not like '%{$term}%' ";
		
		// require that all columns exclude when -term
		$cond = implode(($bool ? 'or' : 'and'), $cond);
		$sql_conds[] = "({$cond})";
	}
	
	if (count($sql_conds) === 0) return 1;
	return implode(' and ', $sql_conds);
}

function sql_in_list($list)
{
	$ci =& get_instance();
	foreach ($list as &$item)
	{
		if (is_integer($item)) continue;
		if (is_float($item)) continue;
		$item = $ci->db->escape($item);
	}
	
	return implode(chr(44), $list);
}

function nl2p($content)
{
	$content = "<p>{$content}</p>";
	// convert double lines (allowing spaces) to paragraphs
	$content = preg_replace('#(\r?\n[\t ]+){2}#s', '</p><p>', $content);
	// convert any remaining single lines to line break
	$content = preg_replace('#(\r?\n){1}#s', '<br />', $content);
	return $content;
}

function gstring($url = null)
{
	$gstring = $_SERVER['QUERY_STRING'];
	if (empty($gstring)) return $url;
	if (strpos($url, $gstring) !== false) return $url;
	if (strpos($url, '?') === false) $url = "{$url}?";
	$url = str_replace('&&', '&', "{$url}&{$gstring}");
	$url = str_replace('?&', '?', $url);
	return $url;
}

?>