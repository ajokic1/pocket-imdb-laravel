<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
        $file = $request->file('image');

        if ($file) {
            $thumbnailPath = self::storeResized($file, 'thumbnails', 200);
            $fullSizePath = self::storeResized($file, 'full_size', 400);

            $image = Image::make([
                'thumbnail' => Storage::url($thumbnailPath),
                'full' => Storage::url($fullSizePath),
            ]);
        } else {
            $image = Image::make([
                'image_url' => $request->image_url,
            ]);
        }

        $movie->image()->save($image);
    }

    private static function storeResized($file, $folder, $size) {
        $fileName = uniqid();
        $image = InterventionImage::make($file)
            ->fit($size, $size)
            ->encode('jpg', 80);
        $path = "movies/$folder/$fileName.jpg";
        Storage::disk('public')->put($path, $image);

        return $path;
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
