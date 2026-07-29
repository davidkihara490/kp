@php
    $phone = config('services.whatsapp.phone', '254700130759');
    $message = config('services.whatsapp.message', 'Hello, I need more information about your services');
    $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $phone) . "?text=" . urlencode($message);
@endphp

<div class="whatsapp-float">
    <div class="whatsapp-tooltip">
        <span class="tooltip-text">Chat with us on WhatsApp</span>
        <span class="tooltip-time">Online 24/7</span>
    </div>
    <a href="{{ $whatsappUrl }}"
        target="_blank"
        class="whatsapp-button"
        aria-label="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>