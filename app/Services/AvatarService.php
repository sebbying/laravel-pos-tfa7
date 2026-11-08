<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Throwable;
class AvatarService
{
    public function store(UploadedFile $file): string
    {
        try {
            $manager = new ImageManager(new Driver());
            // GD auto-orients images and discards metadata when re-encoding.
            $encoded = $manager->read($file->getRealPath())->cover(256, 256)->toJpeg(85);
        } catch (Throwable $exception) {
            report($exception);
            throw ValidationException::withMessages(['avatar' => 'This image could not be processed. Please choose another image.']);
        }
        $path = 'avatars/'.Str::uuid().'.jpg';
        if (! Storage::disk('public')->put($path, (string) $encoded)) {
            throw ValidationException::withMessages(['avatar' => 'The image could not be saved. Please try again.']);
        }
        return $path;
    }
}
