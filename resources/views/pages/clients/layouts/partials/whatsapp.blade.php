<!-- WhatsApp Floating Button -->
<div class="whatsapp-float">
    <div class="whatsapp-tooltip" id="whatsappTooltip">
        <span class="tooltip-text">Chat with us</span>
        <span class="tooltip-time">We're here to help!</span>
    </div>
    <a href="https://wa.me/254712345678?text=Hello!%20I%20need%20help%20with%20a%20parcel%20delivery." 
       class="whatsapp-button" 
       target="_blank" 
       rel="noopener noreferrer"
       aria-label="Chat on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
</div>

<style>
    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        display: flex;
        align-items: center;
    }

    .whatsapp-button {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #25d366, #128C7E);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
        transition: var(--transition);
        position: relative;
        z-index: 2;
        text-decoration: none;
    }

    .whatsapp-button:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 30px rgba(37, 211, 102, 0.4);
        color: white;
        text-decoration: none;
    }

    .whatsapp-button i {
        animation: pulse-whatsapp 2s infinite;
    }

    @keyframes pulse-whatsapp {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    .whatsapp-tooltip {
        background: white;
        border-radius: 50px;
        padding: 10px 18px;
        margin-right: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        position: relative;
        animation: slideInRight 0.3s ease;
        border: 1px solid var(--border-color);
        display: none;
    }

    .whatsapp-tooltip::after {
        content: '';
        position: absolute;
        right: -8px;
        top: 50%;
        transform: translateY(-50%);
        border-left: 8px solid white;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
    }

    .tooltip-text {
        display: block;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
    }

    .tooltip-time {
        display: block;
        font-size: 0.7rem;
        color: var(--primary-color);
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Show tooltip on larger screens */
    @media (min-width: 768px) {
        .whatsapp-tooltip {
            display: block;
        }
    }

    @media (max-width: 768px) {
        .whatsapp-float {
            bottom: 20px;
            right: 20px;
        }

        .whatsapp-button {
            width: 50px;
            height: 50px;
            font-size: 25px;
        }

        .whatsapp-tooltip {
            display: none;
        }
    }
</style>

<script>
    // Hide tooltip after 8 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const tooltip = document.getElementById('whatsappTooltip');
        if (tooltip) {
            setTimeout(function() {
                tooltip.style.display = 'none';
            }, 8000);
        }
    });
</script>