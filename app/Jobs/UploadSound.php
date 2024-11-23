<?php

namespace App\Jobs;

use App\Helpers\S3Utils;
use App\Models\Chapter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadSound implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    protected $fileName;
    protected $extension;
    protected string $chapterId;


    /**
     * Create a new job instance.
     */
    public function __construct($path, $chapterId, $fileName, $extension)
    {
        $this->path = $path;
        $this->chapterId = $chapterId;
        $this->fileName = $fileName;
        $this->extension = $extension;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = Storage::get($this->path);
        $utils = new S3Utils();
        $path = $utils->uploadLargeFile( 'sounds', $file, $this->fileName, $this->extension );
        $chapter = Chapter::find($this->chapterId);
        $chapter->update(['sound' => $path]);
        Storage::delete($this->path);
    }
}
