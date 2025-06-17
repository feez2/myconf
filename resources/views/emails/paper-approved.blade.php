@component('mail::message')
# Paper Approved for Proceedings

Dear {{ $paper->author->name }},

We are pleased to inform you that your paper **"{{ $paper->title }}"** has been approved for inclusion in the conference proceedings.

@if($paper->proceeding)
The proceedings will be published on **{{ $paper->proceeding->publication_date->format('F j, Y') }}**.
@endif

@component('mail::button', ['url' => $url])
View Paper Details
@endcomponent

Thank you for your contribution to {{ $paper->conference->title }}.

Best regards,
The Program Committee
@endcomponent
