<?php

namespace App\Http\Controllers\Authed;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $carousels = Carousel::paginate($request->get('per-page', config('app.paginate_default')));

        return view('carousel.index', compact('carousels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('carousel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        Carousel::create([
            'name'   => $request->get('name'),
            'slides' => [],
        ]);

        session()->flash('alert.success', 'Created Carousel');

        return redirect()->route('authed.carousel.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Carousel $carousel
     * @return Response
     */
    public function show(Carousel $carousel)
    {
        return view('carousel.show', compact('carousel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Carousel $carousel
     * @return Response
     */
    public function edit(Carousel $carousel)
    {
        return view('carousel.edit', compact('carousel'));
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
        $carousel->update([
            'name' => $request->get('name'),
        ]);

        session()->flash('alert.success', 'Updated Carousel');

        return redirect()->route('authed.carousel.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Carousel $carousel
     * @return Response
     */
    public function destroy(Carousel $carousel)
    {
        $carousel->delete();

        session()->flash('deleted');

        return redirect()->back();
    }
}
