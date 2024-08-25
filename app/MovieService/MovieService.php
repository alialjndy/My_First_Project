<?php
namespace App\MovieService;

use App\Exceptions\MovieUpdateFailedException;
use App\Models\Movie;
use Exception;

class MovieService{
    public function createMovie($data){
        return Movie::create($data);
    }
    public function updateMovie(Movie $movie , $data){
        // The method update return true if the movie updated successfully and return false other wise.
        try{
            if($movie->update($data)){
                return response()->json($movie , 200);
            }else{
                throw new MovieUpdateFailedException('Failed to update movie.');
            }
        }catch(MovieUpdateFailedException $e){
            return response()->json(['error' => $e->getMessage()] , 500);
        }
    }
    public function deleteMovie(Movie $movie){
        try{
            // attept to delete the film
            if($movie->delete()){
                // success delete the film
                return response()->json(['success'=>'movie deleted successfully.'], 200);
            }
        }catch(Exception $e){
            // failed delete the film
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }
}
?>
