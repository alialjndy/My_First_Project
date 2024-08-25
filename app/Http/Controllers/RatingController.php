<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\MovieService\RatingService;
use Illuminate\Http\Request;
use App\Models\newRating;
use Illuminate\Support\Facades\Auth ;

class RatingController extends Controller
{
    protected $ratingService;
    public function __construct(RatingService $ratingService){
        $this->ratingService = $ratingService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::check()){
            $rating = newRating::all();
            return response()->json($rating , 200);
        }else{
            return response()->json([
                'status'=>false ,
                'message'=>'please log in to can show ratings',
            ],422);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validationData = $request->validate([
                'user_id'=>'required',
                'movie_id'=>'required',
                'rating'=> 'required|integer|min:1|max:5',
                'review'=>'string',
            ]);
            if(Auth::check()){
                $movie = Movie::findOrFail($request->movie_id);
                $newRating = $request->rating;
                $movie->rating = ($movie->rating + $newRating) ;
                $movie->save();

                $rating = $this->ratingService->createRating($validationData);
                return response()->json($rating, 201);
            }else{
            return response()->json([
                'status'=>false ,
                'message'=>'please log in to can show ratings',
            ]);
            }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(Auth::check()){
            $rating = newRating::findOrFail($id);
            return response()->json($rating , 201);
        }else{
            return response()->json([
                'status'=>false ,
                'message'=>'please log in to can Create rating',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
            'status'=>false ,
            'message'=>'You Cant Update the rating',
        ],403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'status'=>false ,
            'message'=>'You cant delete the rating ',
        ],403);
    }
}
