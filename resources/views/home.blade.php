{{--@foreach($data as $dat)--}}
{{--    <div>--}}
{{--        <h1><a href="books/{{ $dat->id }}">{{ $dat->name }}</a></h1>--}}
{{--        @foreach($dat->authors as $author)--}}
{{--        <p>{{ $author }}</p>--}}
{{--        @endforeach--}}
{{--        <p>{{ $dat->comment_count }}</p>--}}
{{--    </div>--}}

{{--@endforeach--}}

<?php

print_r($data);
