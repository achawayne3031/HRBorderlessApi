<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;




class ProcessJobApplicationFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected JobApplication $jobApplication;
    protected ?string $tempResumePath;
    protected ?string $tempCoverLetterPath;

    /**
     * Create a new job instance.
     */
    public function __construct(JobApplication $jobApplication, ?string $tempResumePath, ?string $tempCoverLetterPath)
    {
        $this->jobApplication = $jobApplication;
        $this->tempResumePath = $tempResumePath;
        $this->tempCoverLetterPath = $tempCoverLetterPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $finalResumePath = null;
        $finalCoverLetterPath = null;

        // 1. Process Resume
        if ($this->tempResumePath && Storage::exists($this->tempResumePath)) {
            $originalExtension = pathinfo($this->tempResumePath, PATHINFO_EXTENSION);
            $resumeFileName = 'resumes/' . Str::uuid() . '.' . $originalExtension;
            Storage::move($this->tempResumePath, 'public/' . $resumeFileName);
            $finalResumePath = 'public/' . $resumeFileName;
        }

        // 2. Process Cover Letter
        if ($this->tempCoverLetterPath && Storage::exists($this->tempCoverLetterPath)) {
            $originalExtension = pathinfo($this->tempCoverLetterPath, PATHINFO_EXTENSION);
            $coverLetterFileName = 'cover_letters/' . Str::uuid() . '.' . $originalExtension;
            // Move from temporary storage to permanent public storage
            Storage::move($this->tempCoverLetterPath, 'public/' . $coverLetterFileName);
            $finalCoverLetterPath = 'public/' . $coverLetterFileName; // Store public path
        }

        // 3. Update the JobApplication record with the final paths
        $this->jobApplication->update([
            'resume_url' => $finalResumePath,
            'cover_letter_url' => $finalCoverLetterPath,
        ]);
    }


    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // Log the exception, send a notification to admin, etc.
        \Log::error('Job application file processing failed for application ID: ' . $this->jobApplication->id . '. Error: ' . $exception->getMessage());

        // You might want to delete the temporary files if they still exist
        if ($this->tempResumePath && Storage::exists($this->tempResumePath)) {
            Storage::delete($this->tempResumePath);
        }
        if ($this->tempCoverLetterPath && Storage::exists($this->tempCoverLetterPath)) {
            Storage::delete($this->tempCoverLetterPath);
        }

        // You could also update the job application status to 'failed_processing'
        $this->jobApplication->update(['status' => 0]);
    }
}
