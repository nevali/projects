<?php

$orgs = array();
$projects = array();
$base = realpath(dirname(__FILE__) . '/../projects');
$d = opendir($base);
while(($de = readdir($d)) !== false)
{
	if(substr($de, 0, 1) == '.' ||
	   !is_dir($base . '/' . $de) ||
	   !file_exists($base . '/' . $de . '/project.json'))
	{
		continue;
	}
	$info = json_decode(file_get_contents($base . '/' . $de . '/project.json'), true);
	$info['name'] = $de;
	if(isset($info['role']) && !is_array($info['role']))
	{
		$info['role'] = array($info['role']);
	}
	if(isset($info['org']))
	{
		if(!is_array($info['org']))
		{
			$info['org'] = array($info['org']);
		}
		$orglist = array();
		foreach($info['org'] as $name)
		{
			if(isset($orgs[$name]))
			{
				$orglist[$name] = $orgs[$name];
			}
			else
			{
				$path = realpath(dirname(__FILE__) . '/../orgs') . '/' . $name . '/org.json';
				if(file_exists($path))
				{
					$orgs[$name] = json_decode(file_get_contents($path), true);
					$orgs[$name]['name'] = $name;
					$orglist[$name] = $orgs[$name];
				}
			}
		}
		$info['org'] = $orglist;
	}
	if(isset($info['org']) && !count($info['org']))
	{
		unset($info['org']);
	}
	$key = sortdate($info) . '-' . $de;
	$projects[$key] = $info;
}
closedir($d);
krsort($projects);

foreach($projects as $key => $info)
{
	echo '<section class="project" id="' . _e($info['name']) . '">' . "\n";
	echo '<h1>';

	if(isset($info['url']))
	{
		echo '<a href="' . _e($info['url']) . '">';
	}
	e($info['title']);
	if(isset($info['url']))
	{
		echo '</a>';
	}
	echo '</h1>' . "\n";
	$basepath = dirname(__FILE__) . '/../projects/' . $info['name'] . '/';
	$img = null;
	if(file_exists($basepath . $info['name'] . '.jpeg'))
	{
		$img = 'projects/' . $info['name'] . '/' . $info['name'] . '.jpeg';
	}
	else if(file_exists($basepath . 'thumb.jpeg'))
	{
		$img = 'projects/' . $info['name'] . '/thumb.jpeg';
	}
	if(strlen($img))
	{
		echo '<div class="img">';
		if(isset($info['url']))
		{
			echo '<a href="' . _e($info['url']) . '">';
		}
		echo '<img src="' . _e($img) . '" alt="">';
		if(isset($info['url']))
		{
			echo '</a>';
		}
		echo '</div>' . "\n";
	}
	echo '<ul class="fa-ul">' . "\n";
	$date = humandate($info);
	if($date !== false && strlen($date))
	{
		echo '<li class="date"><span class="fa fa-li fa-calendar"></span>' . _e($date) . '</li>' . "\n";
	}
	if(isset($info['role']))
	{
		$list = array();
		foreach($info['role'] as $role)
		{
			$list[] = _e($role);
		}
		echo '<li class="role"><span class="fa fa-li fa-user"></span><span class="i">' . implode('&nbsp;• ', $list) . '</span></li>'  . "\n";
	}
	if(isset($info['org']))
	{
		$list = array();
		foreach($info['org'] as $org)
		{
			if(isset($org['url']))
			{
				$list[] = '<a href="' . _e($org['url']) . '">' . _e($org['title']) . '</a>';
			}
			else
			{
				$list[] = _e($org['title']);
			}
		}
		echo '<li class="org"><span class="fa fa-li fa-building"></span>' . implode('&nbsp;• ', $list) . '</li>' . "\n";
	}
	if(isset($info['url']))
	{
		$url = parse_url($info['url']);
		$nice = $url['host'];
		if(substr($nice, 0, 4) == 'www.')
		{
			$nice = substr($nice, 4);
		}
		$path = explode('/', trim(@$url['path'], '/'));
		if(count($path) > 2)
		{
			$nice .= '/' . $path[0] . '/' . $path[1] . '/…';
		}
		else
		{
			$nice .= @$url['path'];
		}
		echo '<li class="link"><span class="fa fa-li fa-globe"></span><a href="' . _e($info['url']) . '">' . _e(rtrim($nice, '/')) . '</a></li>' . "\n";
	}
	echo '</ul>' . "\n";
	echo '</section>' . "\n";
}
