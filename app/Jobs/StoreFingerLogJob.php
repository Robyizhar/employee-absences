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
        try {

            $line = now()->toDateTimeString() . ' => ' . json_encode($this->data, JSON_PRETTY_PRINT);

            $ok = Storage::append($this->path, $line);

            if (!$ok)
                \Log::error("APPEND FAIL (return false) for {$this->path}");

            Storage::append(
                $this->path,
                now()->toDateTimeString() . ' => ' . json_encode($this->data, JSON_PRETTY_PRINT)
            );
        } catch (\Throwable $th) {
            \Log::error("APPEND EXCEPTION: " . $e->getMessage());
        }
    }
}
