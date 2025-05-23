<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCompanyNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $company;

   
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Company Registered: ' . $this->company->name,
        );
    }

    
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.companies.new_company', 
            with: [
                'companyName' => $this->company->name,
                'companyEmail' => $this->company->email,
                'companyWebsite' => $this->company->website,
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