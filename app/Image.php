<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use  Intervention\Image\Facades\Image as InterventionImage;

class Image extends Model
{
    protected $fillable = ['thumbnail', 'full', 'image_url'];

    protected $appends = ['thumbnail_url', 'full_url'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public static function storeMovieImage($movie, $request)
    {
        if ($request->has('image_url')) {
            $image = Image::make([
                'image_url' => $request->image_url,
            ]);
        } else {
            $fileName = uniqid();

            $file = $request->file('image');

            $thumbnail = InterventionImage::make($file)
                ->fit(200, 200)
                ->encode('jpg', 80);
            $thumbnailPath = "movies/thumbnails/$fileName.jpg";
            Storage::disk('public')->put($thumbnailPath, $thumbnail);

            $fullSize = InterventionImage::make($file)
                ->fit(400, 400)
                ->encode('jpg', 80);
            $fullSizePath = "movies/full_size/$fileName.jpg";
            Storage::disk('public')->put($fullSizePath, $fullSize);

            $image = Image::make([
                'thumbnail' => Storage::url($thumbnailPath),
                'full' => Storage::url($fullSizePath)
            ]);
        }

        $movie->image()->save($image);
    }

    public function getThumbnailUrlAttribute()
    {
        $thumbnail = $this->thumbnail;

        return $thumbnail ? asset($thumbnail) : $this->image_url;
    }

    public function getFullUrlAttribute()
    {
        $full = $this->full;
        
        return $full ? asset($full) : $this->image_url;
    }
}
