<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyHourReport extends Mailable
{
    use Queueable, SerializesModels;

    protected $totalHour;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($totalHour)
    {
        $this->totalHour = $totalHour;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Weekly Hour Report',
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.weeklyHourReport')->with([
            'totalHour' => $this->totalHour,
        ]);
    }
}
