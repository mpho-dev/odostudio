@extends('layouts.email')

@section('content')
    <div class="greeting">New Booking Request</div>
    
    <div class="message-body">
        {!! nl2br($emailBody) !!}
    </div>
    
    <div class="details-box">
        <h3 class="details-title">Request Details</h3>
        
        <div class="detail-row">
            <span class="detail-label">Client Name:</span>
            <span class="detail-value">{{ $bookingRequest->name }} {{ $bookingRequest->surname }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Email Address:</span>
            <span class="detail-value">{{ $bookingRequest->email }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Phone Number:</span>
            <span class="detail-value">{{ $bookingRequest->phone }}</span>
        </div>
        
        @if($bookingRequest->notes)
        <div class="detail-row" style="margin-top: 15px; border-bottom: none;">
            <span class="detail-label">Client Notes:</span>
            <div class="detail-value" style="margin-top: 10px; font-style: italic; border-left: 2px solid #d4af37; padding-left: 15px; color: #aaaaaa;">
                {{ $bookingRequest->notes }}
            </div>
        </div>
        @endif
    </div>
    
    <p style="font-size: 13px; color: #666666; text-align: center; margin-top: 50px; font-style: italic; letter-spacing: 1px;">
        Please log in to the administrative portal to review and manage this inquiry.
    </p>
@endsection
