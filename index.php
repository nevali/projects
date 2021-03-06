<?php require_once(dirname(__FILE__) . '/lib/common.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Mo McRoberts | Music • Broadcasting • Technology</title>
		<link rel="stylesheet" href="styles.css" type="text/css">
		<link rel="stylesheet" href="print.css" type="text/css" media="print">
	    <link rel="stylesheet" href="font-awesome-4.6.3/css/font-awesome.min.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Assistant:300" rel="stylesheet">
	 	<meta name="viewport" content="width=device-width,initial-scale=1">
	</head>
	<body>
		<header>
			<h1>Mo McRoberts</h1>
			<h2>Music • Broadcasting • Technology</h2>
		</header>
		<main>
			<div id="frontmatter">
	 			<section id="profile">
<?php
if($internal)
{
	readfile(dirname(__FILE__) . '/parts/_profile-internal.html');
}
else
{
	readfile(dirname(__FILE__) . '/parts/_profile.html');
}
?>
	 			</section>
			</div>
	 		<section id="contact">
<?php readfile(dirname(__FILE__) . '/parts/_contact.html'); ?>
	 		</section>

			<section id="projects">
	 			<h1>Projects</h1>
<?php include(dirname(__FILE__) . '/lib/projects.php'); ?>
			</section>

			<section id="publications">
				<h1>Publications</h1>
				<ul class="fa-ul">
					<li class="orcid"><span class="fa fa-li fa-barcode"></span>ORCID: <a href="http://orcid.org/0000-0002-0862-3195">0000-0002-0862-3195</a></li>
				</ul>
<?php include(dirname(__FILE__) . '/lib/publications.php'); ?>
			</section>

			<section id="experience">
				<h1>Employment experience</h1>
<?php include(dirname(__FILE__) . '/lib/experience.php'); ?>
			</section>

 			<section id="interests">
<?php readfile(dirname(__FILE__) . '/parts/_interests.html'); ?>
 			</section>

			<section id="references">
				<h1>References</h1>
<?php readfile(dirname(__FILE__) . '/parts/_references.html'); ?>
			</section>

		</main>
	</body>
</html>