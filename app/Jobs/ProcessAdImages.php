<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessAdImages implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Ad $ad) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
    foreach ($this->ad->images as $image) {
        
        $imagePath = storage_path('app/public/' . $image->path);

        if (file_exists($imagePath)) {
            // Resize باستخدام  مكتبة Intervention Image
            $img = \Image::make($imagePath)->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($imagePath); // Overwrite
        }
    }
    }

}