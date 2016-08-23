<?php

mb_internal_encoding('UTF-8');

function to_utf8_fix_chars($content)
{
	$conversions = array(
		"\xC3\xA2\xE2\x82\xAC\xE2\x80\x9C" => "-",
		"\xC3\xA2\xE2\x82\xAC\xE2\x84\xA2" => "'",
		"\xC3\xA2\xE2\x82\xAC\xE2\x80\x9D" => "-",
		"\xC3\xA2\xE2\x80\x9E\xC2\xA2" => "\xE2\x84\xA2", // trademark
		"\xC3\xA2\xE2\x82\xAC\xC5\x93" => "\"",
		"\xC3\xA2\xE2\x82\xAC\xC2\x9D" => "\"",
		"\xC3\xA2\xE2\x82\xAC\xC2\xA6" => "...",
		"\xC3\xA2\xE2\x82\xAC\xC2\xA2" => "\xE2\x80\xA2", // bullet
		"\xC3\xAF\xE2\x82\xAC\xC2\xAD" => "\xE2\x80\xA2", // bullet
		"\xC3\xA2\xE2\x82\xAC\xCB\x9C" => "'",
		"\xC3\x83\xC2\xA9" => "e",
		"\xC3\x83\xC2\xA0" => "a",
		"\xC3\x82\xC2\xA0" => " ",
		"\xC2\xA1\xC2\xAF" => "'",
		"\xC2\xA1\xC2\xA5" => "'",
		"\xC2\xA1\xC2\xA6" => "'",
		"\xC2\xA1\xC2\xA7" => "\"",
		"\xC2\xA1\xC2\xA8" => "\"",
		"\xC3\x83\xC2\xBC" => "u",
		"\xC3\x83\xC2\xA6" => "ae",
		"\xE2\x80\x93" => "-",
		"\xE2\x80\x98" => "'",
		"\xE2\x80\x99" => "'",
		"\xE2\x80\x9C" => "\"",
		"\xE2\x80\x9D" => "\"",
		"\xC3\xA9" => "e",
		"\xC3\x82" => "",
	);

	foreach ($conversions as $from => $to)
		$content = str_replace($from, $to, $content);

	return $content;
}

function to_utf8_3b($content)
{
	$encoding = mb_detect_encoding($content);
	if (!$encoding || $encoding === 'ASCII') $encoding = 'Windows-1251';
	$content = mb_convert_encoding($content, 'UTF-8', $encoding);
	$content = to_utf8_fix_chars($content);
	$content = preg_replace('#[\xF0-\xF7]...#s', '', $content);

	return $content;
}

function to_utf8_3b_array(&$array)
{
	foreach ($array as &$v) 
	{
		if (is_array($v))
		{
			to_utf8_3b_array($v);
		}
		else
		{
			$v = to_utf8_3b($v);
		}
	}
}

to_utf8_3b_array($_COOKIE);
to_utf8_3b_array($_POST);
to_utf8_3b_array($_GET);

?>