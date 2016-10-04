<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

function e($str)
{
	echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function _e($str)
{
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function ymdhms($datestr)
{
	if(strlen($datestr) == 4)
	{
		return $datestr . '-01-01 00:00:00';
	}
	if(strlen($datestr) == 7)
	{
		return $datestr . '-01 00:00:00';
	}
	if(strlen($datestr) == 10)
	{
		return $datestr . ' 00:00:00';
	}
	return $datestr;
}

function sortdate($info)
{
	$now = strftime('%Y-%m-%d %H:%M:%S');
	if(isset($info['sortdate']))
	{
		$start = ymdhms($info['sortdate']);
		$end = $start;
	}
	else if(isset($info['date']))
	{
		$start = ymdhms($info['date']);
		$end = $start;
	}
	else if(isset($info['end']))
	{
		$start = ymdhms($info['start']);
		$end = ymdhms($info['end']);
	}
	else if(isset($info['start']))
	{
		$start = ymdhms($info['start']);
		$end = $now;
	}
	else
	{
		$start = $end = $now;
	}
	return $end . '-' . $start;
}

function humandatestr($str)
{
	$dt = strtotime(ymdhms($str));
	if(strlen($str) <= 4)
	{
		return strftime('%Y', $dt);
	}
	return strftime('%B %Y', $dt);
}

function humandate($info)
{
	if(isset($info['date']))
	{
		return humandatestr($info['date']);
	}
	if(isset($info['start']) && isset($info['end']))
	{
		return humandatestr($info['start']) . '—' . humandatestr($info['end']);
	}
	if(isset($info['start']))
	{
		return humandatestr($info['start']) . '—present';
	}
}

function getorg($name)
{
	static $orgs = array();

	if(isset($orgs[$name]))
	{
		return $orgs[$name];
	}
	$base = realpath(dirname(__FILE__) . '/../orgs') . '/';
	$path = $base . $name . '/org.json';
	if(file_exists($path))
	{
		$data = json_decode(file_get_contents($path), true);
		$data['name'] = $name;
		if(file_exists($base . $name . '/' . $name . '.jpeg'))
		{
			$data['thumb'] = 'orgs/' . $name . '/' . $name . '.jpeg';
		}
		$orgs[$name] = $data;
		return $data;
	}
	return false;
}
