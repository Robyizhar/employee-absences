<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreFingerLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $path;
    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(string $path, array $data)
    {
        $this->path = $path;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info($this->path);
        \Log::info('DISK PATH: ' . Storage::disk('local')->path($this->path));
        Storage::append(
            $this->path,
            now()->toDateTimeString() . ' => ' . json_encode($this->data, JSON_PRETTY_PRINT)
        );
    }
}
