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
     * @return Carousel
     */
    public function show(Carousel $carousel)
    {
        return $carousel;
    }

    /**
     * Update the specified resource in storage.
     * @param Request  $request
     * @param Carousel $carousel
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(Request $request, Carousel $carousel)
    {
        $new_slides = $request->get('slides');
        $old_slides = $carousel->slides;

        $missing = array_diff($old_slides, $new_slides);
        foreach($missing as $missing_slide){
            $url = parse_url($missing_slide);

            try{
                unlink(public_path($url['path']));
            }catch(\Exception $e){
                // Probably file is missing. Can't remove the file
                logger()->error('Could not remove file ' . public_path($url['path']));
                logger()->error($e);
            }
        }

        $carousel->update($request->only(['slides']));

        return \response(['message' => 'OK']);
    }

    public function upload_image(Request $request, Carousel $carousel)
    {
        $originalImages = $request->file('carousel_files');
        foreach ($originalImages as $originalImage) {
            $imageObject  = \Image::make($originalImage);
            $originalPath = storage_path('app/public/images/');

            $file_name  = time() . '-' . $originalImage->getClientOriginalName();
            $save_name  = $originalPath . $file_name;
            $publicPath = asset("storage/images/{$file_name}");

            $imageObject->save($save_name);

            // Append the new slide
            $slides   = $carousel->slides;
            $slides[] = $publicPath;

            $carousel->update(['slides' => $slides]);
        }

        $carousel->refresh();

        return response(['status' => true]);
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
