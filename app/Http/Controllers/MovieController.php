<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\MovieService\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movieService ;
    /**
     * constructor to inject MovieService class.
     * @param \App\MovieService\MovieService $movieService
     * @return void
     */
    public function __construct(MovieService $movieService){
        $this->movieService = $movieService;
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $genre = $request->input('genre');
        $director = $request->input('director');
        $perpage = $request->input('per_page', 5);
        $sortType = $request->input('sort_type', 'asc');

        $movie = Movie::query();

        //If the genre is available, apply filtering
        if($genre){
            $movie->byGenre($genre);
        }
        //If the director is available, apply filtering
        if($director){
            $movie->byDirector($director);
        }
        // If the view is descending
        if($sortType === 'desc'){
            $movie->orderBy('release_year', 'desc');
        }else{
            // If the view is ascending
            $movie->orderBy('release_year','asc');
        }
        //
        $filterMovies = $movie->paginate($perpage);
        return response()->json($filterMovies , 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validationData = $request->validate([
        'title'=> 'required|string|max:255',
        'director'=> 'required|string|max:40',
        'genre' => 'required|string',
        'release_year'=>'required|integer',
        'description'=>'required|string',
        'rating'=>'integer|nullable',
        ]);
        $movie = $this->movieService->createMovie($validationData);
        return response()->json($movie , 201);
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie, 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Movie $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Movie $movie)
    {
        $validationData = $request->validate([
        'title'=> 'required|string|max:255',
        'director'=> 'required|string|max:40',
        'genre' => 'required|string',
        'release_year'=>'required|integer',
        'description'=>'required|string',
        ]);
        $this->movieService->updateMovie($movie , $validationData);
        return response()->json($movie , 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $movie = Movie::findOrFail($id);
        $this->movieService->deleteMovie($movie);
        return response()->json('movie deleted successfully', 200);
    }
}
