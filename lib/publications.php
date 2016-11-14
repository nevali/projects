<?php

$publications = array();
$base = realpath(dirname(__FILE__) . '/../publications');
$d = opendir($base);
while(($de = readdir($d)) !== false)
{
	if(substr($de, 0, 1) == '.' ||
		!is_dir($base . '/' . $de) ||
		!file_exists($base . '/' . $de .'/publication.json'))
	{
		continue;
	}
	$info = json_decode(file_get_contents($base . '/' . $de . '/publication.json'), true);
	if(file_exists($base . '/' . $de . '/' . $de . '.jpeg'))
	{
		$info['thumb'] = 'publication/' . $de . '/' . $de . '.jpeg';
	}
	$info['name'] = substr($de, 0, -5);
	if(isset($info['org']))
	{
		if(!is_array($info['org']))
		{
			$info['org'] = array($info['org']);
		}
		$orglist = array();
		foreach($info['org'] as $name)
		{
			$org = getorg($name);
			if($org !== false)
			{
				$orglist[$name] = $org;
			}
		}
		$info['org'] = $orglist;
	}
	if(isset($info['org']) && !count($info['org']))
	{
		unset($info['org']);
	}
	$key = sortdate($info) . '-' . $de;
	$publications[$key] = $info;
}
closedir($d);
krsort($publications);

foreach($publications as $key => $info)
{
	if(!empty($info['hide']))
	{
		continue;
	}
	$img = null;
	if(isset($info['thumb']))
	{
		$img = $info['thumb'];
	}
	else if(isset($info['org']))
	{
		foreach($info['org'] as $data)
		{
			if(isset($data['thumb']))
			{
				$img = $data['thumb'];
				break;
			}
		}
	}

	echo '<section class="experience" id="' . _e($info['name']) . '"';
	if(strlen($img))
	{
		echo ' style="background-image: ';
		echo "url('" . _e($img) . "');";
		echo '"';
	}
	echo '>' . "\n";
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
	if(isset($info['subtitle']))
	{
		echo '<h2>' . _e($info['subtitle']) . '</h2>' . "\n";
	}
	$basepath = dirname(__FILE__) . '/../publications/' . $info['name'] . '/';
	echo '<ul class="fa-ul"';
	echo ">\n";
	if(isset($info['type']))
	{
		echo '<li class="type"><span class="fa fa-li fa-book"></span>' . _e($info['type']) . '</li>' . "\n";
	}
	if(isset($info['isbn']))
	{
		echo '<li class="isbn"><span class="fa fa-li fa-barcode"></span>ISBN: ' . _e($info['isbn']) . '</li>' . "\n";
	}
	if(isset($info['role']))
	{
		echo '<li class="role"><span class="fa fa-li fa-user"></span>' . _e($info['role']) . '</li>' . "\n";
	}
	if(isset($info['note']))
	{
		echo '<li class="note"><span class="fa fa-li fa-exclamation-circle"></span>' . _e($info['note']) . '</li>';
	}
	$date = humandate($info);
	if($date !== false && strlen($date))
	{
		echo '<li class="date"><span class="fa fa-li fa-calendar"></span>' . _e($date) . '</li>' . "\n";
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
		echo '<li class="org"><span class="fa fa-li fa-building"></span>' . implode(' • ', $list);
/*
		if(isset($info['dept']))
		{
			$list = array();
			foreach($info['dept'] as $dept)
			{
				if(isset($dept['url']))
				{
					$list[] = '<a href="' . _e($dept['url']) . '">' . _e($dept['title']) . '</a>';
				}
				else
				{
					$list[] = _e($dept['title']);
				}
			}
			echo ' (' . implode(', ', $list) . ')';
		} */
		echo '</li>' . "\n";
	}
	if(isset($info['location']))
	{
		echo '<li class="location"><span class="fa fa-li fa-map-marker"></span>' . _e($info['location']) . '</li>' . "\n";
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
	if(file_exists($base . '/' . $info['name'] . '/desc.html'))
	{
		readfile($base . '/' . $info['name'] . '/desc.html');
	}
	else if(isset($info['desc']))
	{
		echo '<p>' . _e($info['desc']) . '</p>';
	}
	echo '</section>' . "\n";
}
