@extends('layouts.email')

@section('content')
    <div class="greeting">Hello {{ $booking->photographer->name }},</div>
    
    <div class="message-body">
        {!! nl2br($emailBody) !!}
    </div>
    
    <div class="details-box">
        <h3 class="details-title">Call Sheet Overview</h3>
        
        <div class="detail-row">
            <span class="detail-label">Client</span>
            <span class="detail-value">{{ $booking->bookingRequest->name }} {{ $booking->bookingRequest->surname }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Call Time</span>
            <span class="detail-value">{{ $booking->event_date->format('l, F j, Y — g:i A') }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Location</span>
            <span class="detail-value">{{ $booking->location }}</span>
        </div>
        
        @if ($booking->investmentTier)
        <div class="detail-row" style="margin-top: 20px; padding-top: 20px;">
            <span class="detail-label">Service Tier</span>
            <span class="detail-value" style="color: #d4af37;">{{ $booking->investmentTier->name }}</span>
        </div>
        @endif
        
        @if($booking->bookingRequest->notes)
        <div class="detail-row" style="margin-top: 20px; border-bottom: none;">
            <span class="detail-label">Directives</span>
            <div class="detail-value" style="margin-top: 10px; font-weight: normal; font-style: italic; color: #aaaaaa; border-left: 2px solid #d4af37; padding-left: 15px;">
                {{ $booking->bookingRequest->notes }}
            </div>
        </div>
        @endif
    </div>
    
    <p style="font-size: 11px; color: #666666; text-align: center; margin-top: 50px; text-transform: uppercase; letter-spacing: 2px;">
        Access the Master Schedule for complete production details
    </p>
@endsection
