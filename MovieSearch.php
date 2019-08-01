<?php
/*
*	A simple class for performing searches on the OMDBAPI
*	TODO: Make Movie Class
*	Authored by Rory Cartwright
*	GPL2: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
*/

require_once('SimpleRESTResource.php');
	
class MovieSearch
{
	protected $resource;
	protected $results;

	public function __construct($api_key)
	{
		$this->resource = new SimpleRESTResource('http://www.omdbapi.com', $api_key);
		$this->results = array();
	}
	
	//Returns the first 10 search results for each term provided
	//Todo: add pagination support
	public function search($terms)
	{
		//We lack search terms
		if(empty($terms))
			return false;
			
		//This class is limited to searching for movies
		$args = array(
			'type' => 'movie',
		);
		
		//Reset our results
		$this->results = array();
		
		//If we're passed a single term as a string
		if(is_string($terms))
		{
			$args['s'] = $terms;
			$result = $this->resource->request($args);
	
			//If we've received a successful response parse the results
			if(!empty($result['Response']) && $result['Response'])
				$this->results = $this->parseSearchResults($result['Search']);
			
			//return our movies
			return $this->results;	
		//if we're passed terms as a key/value array	
		}elseif(is_array($terms))
		{
			$results = array();
			foreach($terms as $term)
			{
				$args['s'] = $term;
				$result = $this->resource->request($args);
				
				//If we get a successful response merge the results into our array
				//Its numerically indexed so its additive
				if(!empty($result['Response']) && $result['Response'])
					$results = array_merge($results, $result['Search']);
				
			}
			$this->results = $this->parseSearchResults($results);
			return $this->results;
		}
		
		return false;
	}
	
	//Builds a MovieSearchResult from an array returned by a RESTResource
	protected function parseSearchResults($results)
	{
		$movies = array();
		//If we have results
		if(!empty($results) && is_array($results))
		{
			//Loop through all the movies
			foreach($results as $movie)
			{
				//And get their details
				$movieDetails = $this->getMovieByID($movie['imdbID']);
				if($movieDetails)
					$movies[] = $movieDetails; 
			}
		}
		return $movies;
	}
	
	protected function getMovieByID($id)
	{
		if(empty($id))
			return false;
		
		if(is_string($id))
		{
			$request = $this->resource->request(array('i' => $id));
			return ($request['Response'] ? $request : false );
		}
		
		return false;
	}
}
?>