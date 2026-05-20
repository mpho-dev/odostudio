@extends('layouts.email')

@section('content')
    <div class="greeting">New Inquiry Received</div>
    
    <div class="message-body">
        A new production inquiry has been logged for <strong>{{ $bookingRequest->interest_name }}</strong>.
    </div>
    
    <div class="details-box">
        <h3 class="details-title">Client Information</h3>
        <div class="detail-row"><span class="detail-label">Name:</span> <span class="detail-value">{{ $bookingRequest->name }} {{ $bookingRequest->surname }}</span></div>
        <div class="detail-row"><span class="detail-label">Email:</span> <span class="detail-value">{{ $bookingRequest->email }}</span></div>
        <div class="detail-row"><span class="detail-label">Phone:</span> <span class="detail-value">{{ $bookingRequest->phone }}</span></div>
        
        <h3 class="details-title" style="margin-top: 25px;">Production Details</h3>
        <div class="detail-row"><span class="detail-label">Concept:</span> <span class="detail-value">{{ $bookingRequest->event_type }}</span></div>
        <div class="detail-row"><span class="detail-label">Target Date:</span> <span class="detail-value">{{ $bookingRequest->event_date?->format('M d, Y') ?? 'TBD' }}</span></div>
        <div class="detail-row"><span class="detail-label">Location:</span> <span class="detail-value">{{ $bookingRequest->event_location ?? 'TBD' }}</span></div>
        
        @if($bookingRequest->notes)
        <div class="detail-row" style="margin-top: 15px; border-bottom: none;">
            <span class="detail-label">Directives:</span>
            <div class="detail-value" style="margin-top: 10px; font-style: italic; border-left: 2px solid #d4af37; padding-left: 15px; color: #aaaaaa;">
                {{ $bookingRequest->notes }}
            </div>
        </div>
        @endif
    </div>
    
    <div class="btn-container">
        <a href="{{ route('photographer.requests') }}" class="btn">View Inquiry Dashboard</a>
    </div>
@endsection
