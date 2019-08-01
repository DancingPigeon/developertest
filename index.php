<?php
	/*
	*	A simple script for outputting results from the OMDB API
	*	TODO: Implement caching
	*	Authored by Rory Cartwright
	*	GPL2: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
	*/

require_once('MovieSearch.php');

$termsToColours = array(
	'red' => 'bg-danger text-light',
	'green' => 'bg-success text-light',
	'blue' => 'bg-primary text-light',
	'yellow' => 'bg-warning text-light',
);

$terms = array(
	'red',
	'green',
	'blue',
	'yellow',
);
$omdbapikey = '8094924b';

$ms = new MovieSearch($omdbapikey);
$results = $ms->search($terms);

//Find the first occurring term in a title
function matchTerm($title, $terms)
{
	//return the first matching terms=
	if(preg_match('/'.implode("|", $terms).'/i', $title, $matches))
		return strtolower($matches[0]);
		
	//How did we get here...
	return false;
}

//Render a bootstrap card for a movie
function renderMovieCard($movie, $classes = '')
{
	echo('<div class="col-4">');
	echo('<div class="card ' . $classes . '">');
		if(!empty($movie['Poster']))
			echo('<img class="card-img-top" src="'. $movie['Poster'] .'" alt="Poster of ' . $movie['Title'] . '" />');
		echo('<div class="card-body">');
			echo('<h4 class="card-title">'. $movie['Title'] .'</h4>');
			echo('<h5 class="card-subtitle">'.$movie['Director'].' - '. $movie['Year'] .'</h5>');
			echo('<p class="card-text">'. $movie["Plot"] .'</p>');
		echo('</div>');
	echo('</div>');
	echo('</div>');
}

include('header.php');
?>
	<div class="container">
		<div class="row justify-content-center">
<?php
if(!empty($results))
{
	//If we have movies
	//Print them out
	?>	
	<?php	
	foreach($results as $movie)
	{
		$class = $termsToColours[matchTerm($movie['Title'], $terms)];
		renderMovieCard($movie, $class);
	}
	?>
	<?php
}else
{
	?>
		<div class="col text-center">
			<p class="display-4">No results ;(</p>
		</div>
	<?
	//We don't have any results
}
	?>
		</div> <!--Close row -->
	</div> <!-- Close container -->
<?php
include('footer.php');
?>
