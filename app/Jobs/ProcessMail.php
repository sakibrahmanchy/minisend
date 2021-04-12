<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Mail\Generic;
use App\Models\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class ProcessMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected Mail $mail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mail $mail)
    {
        $this->mail  = $mail;
    }

    /**
     * Execute the job.p
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Illuminate\Support\Facades\Mail::to([$this->mail->to])
                ->send(new Generic($this->mail));

            $this->mail->update([
                'status' => Status::SENT,
            ]);
        } catch (\Throwable $exception) {
            $this->failed($exception);
        }
    }

    public function failed(\Throwable $throwable)
    {
        preg_match('/<Message>[\s\S]*?<\/Message>/', $throwable->getMessage(), $errorMessages);
        $errorMessage =  count($errorMessages) > 0 ?$errorMessages[0] : $throwable->getMessage();
        $this->mail->update([
            'status' => Status::FAILED,
            'failure_reason' => Str::limit($errorMessage, 150),
        ]);
    }
}
