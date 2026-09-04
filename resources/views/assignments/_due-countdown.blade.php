@if($assignment->due_at)
<span class="value">{{ $assignment->due_at->format('d M Y, h:i A') }}</span>
<span class="asg-countdown"
      data-asg-due="{{ $assignment->due_at->toIso8601String() }}"
      title="Time remaining until due date">—</span>
@else
<span class="value">No due date</span>
@endif
