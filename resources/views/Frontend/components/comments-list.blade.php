{{-- Show pinned comments first --}}
@foreach($pinnedComments as $comment)
    @include('Frontend.components.comments-item', ['comment' => $comment])
@endforeach

{{-- Show regular comments --}}
@foreach($regularComments as $comment)
    @include('Frontend.components.comments-item', ['comment' => $comment])
@endforeach