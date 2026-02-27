<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class NewPostPublished extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Post $post,
        public Subscriber $subscriber
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📝 New Post: ' . $this->post->title,
            replyTo: [
                new Address('hi@sourav.dev', 'Sourav'),
            ]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-post-published',
        );
    }
}
