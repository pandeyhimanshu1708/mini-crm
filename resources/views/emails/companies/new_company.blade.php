@component('mail::message')
# New Company Registered!

A new company, **{{ $companyName }}**, has been registered in the CRM.

**Details:**
* **Name:** {{ $companyName }}
* **Email:** {{ $companyEmail ?? 'N/A' }}
* **Website:** {{ $companyWebsite ?? 'N/A' }}

You can view the company details in the admin panel.

@component('mail::button', ['url' => route('companies.show', $company->id)])
View Company
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent