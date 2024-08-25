<?php
namespace App\MovieService;

use App\Models\Movie;
use App\Models\newRating;

class RatingService{
    public function createRating($data){
        return newRating::create($data);
    }
}
?>
