@component('mail::message')
# Paper Decision: {{ $paper->title }}

Dear {{ $paper->author->name }},

We are writing to inform you about the decision regarding your paper submission to {{ $paper->conference->title }}.

## Decision
**Status:** {{ ucfirst($paper->status) }}

@if($paper->status === 'accepted')
Congratulations! Your paper has been accepted for publication in the conference proceedings.

@if($paper->camera_ready_deadline)
**Camera-ready Deadline:** {{ $paper->camera_ready_deadline->format('F j, Y') }}

Please submit your camera-ready version by this deadline. You can do this through the conference management system.
@endif

@elseif($paper->status === 'revision_required')
Your paper requires revisions before it can be accepted. Please review the decision notes below and submit a revised version.

@else
We regret to inform you that your paper has not been accepted for publication in this conference.

@endif

## Decision Notes
{{ $paper->decision_notes }}

@if($paper->status === 'accepted')
## Next Steps
1. Submit your camera-ready version through the conference management system
2. Complete the copyright form
3. Ensure all author information is correct
4. Make any final revisions requested by the reviewers

@component('mail::button', ['url' => route('papers.camera-ready-form', $paper)])
Submit Camera-Ready Version
@endcomponent

@elseif($paper->status === 'revision_required')
## Next Steps
1. Review the decision notes carefully
2. Address all reviewer comments
3. Submit a revised version through the conference management system

@component('mail::button', ['url' => route('papers.edit', $paper)])
Submit Revised Version
@endcomponent

@endif

If you have any questions, please don't hesitate to contact the conference chairs.

Best regards,<br>
{{ $paper->conference->title }} Program Committee
@endcomponent
