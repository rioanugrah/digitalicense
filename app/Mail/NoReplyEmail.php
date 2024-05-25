<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoReplyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $confirmation,$billing_code,$billing_name,$billing_email,$billing_amount,$billing_status,
        $comment
    )
    {
        $this->confirmation = $confirmation;
        $this->billing_code = $billing_code;
        $this->billing_name = $billing_name;
        $this->billing_email = $billing_email;
        $this->billing_amount = $billing_amount;
        $this->billing_status = $billing_status;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.noreply')
        //             ->with([
        //                 'billing_code' => $this->billing_code,
        //                 'billing_name' => $this->billing_name,
        //                 'billing_email' => $this->billing_email,
        //                 'billing_amount' => $this->billing_amount,
        //                 'billing_status' => $this->billing_status,
        //                 'comment' => $this->comment,
        //             ]);
        return $this->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'))
                   ->view('emails.noreply')
                   ->subject($this->confirmation)
                   ->with(
                    [
                        'billing_code' => $this->billing_code,
                        'billing_name' => $this->billing_name,
                        'billing_email' => $this->billing_email,
                        'billing_amount' => $this->billing_amount,
                        'billing_status' => $this->billing_status,
                        'comment' => $this->comment,
                    ]);
    }
}
