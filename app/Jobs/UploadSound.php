<?php

namespace App\Jobs;

use App\Helpers\S3Utils;
use App\Models\Chapter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadSound implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected string $chapterId;

    /**
     * Create a new job instance.
     */
    public function __construct($file, $chapterId)
    {
        $this->file = $file;
        $this->chapterId = $chapterId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $utils = new S3Utils();
        $path = $utils->uploadLargeFile( 'sounds', $this->file);
        $chapter = Chapter::find($this->chapterId);
        $chapter->update(['sound' => $path]);
    }
}
