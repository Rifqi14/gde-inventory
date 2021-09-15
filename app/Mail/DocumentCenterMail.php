<?php

namespace App\Mail;

use App\Models\DocumentCenterDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentCenterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $id;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        return $this->from(env('MAIL_USERNAME'))->subject($data->subject)->view('admin.mail.index', compact('data'));
    }
}
