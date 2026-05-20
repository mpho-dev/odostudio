@extends('layouts.email')

@section('content')
    <div class="message-body">
        {!! $emailBody !!}
    </div>
@endsection
