<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Le sujet de l'email
     */
    public string $custom_subject;

    /**
     * Le titre de l'email
     */
    public string $title;

    /**
     * Le contenu de l'email
     */
    public string $emailContent;

    /**
     * Données supplémentaires
     */
    public array $data;

    /**
     * Create a new message instance.
     */
    public function __construct(string $custom_subject, string $title, string $emailContent, array $data = [])
    {
        $this->custom_subject= $custom_subject;
        $this->title = $title;
        $this->emailContent = $emailContent;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->custom_subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.custom',
            with: [
                'title' => $this->title,
                'emailContent' => $this->emailContent,
                'data' => $this->data,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}