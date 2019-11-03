<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Carousel $carousel
     * @return Response
     */
    public function show(Carousel $carousel)
    {
        return $carousel;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param Carousel $carousel
     * @return Response
     */
    public function update(Request $request, Carousel $carousel)
    {
        return \response()->json($request->files());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Carousel $carousel
     * @return Response
     */
    public function destroy(Carousel $carousel)
    {
        //
    }
}
