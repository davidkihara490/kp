$(document).ready(function() {

    // ============================================
    // NAVIGATION - Smooth scroll for anchor links
    // ============================================
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const targetId = $(this).attr('href');
        if (targetId === '#') return;

        const targetElement = $(targetId);
        if (targetElement.length) {
            $('html, body').animate({
                scrollTop: targetElement.offset().top - 80
            }, 500);
        }
    });

    // ============================================
    // FAQ ACCORDION
    // ============================================
    $('.faq-question').on('click', function() {
        const faqItem = $(this).closest('.faq-item');
        const icon = $(this).find('i');

        $('.faq-item').not(faqItem).removeClass('active').find('i')
            .removeClass('bi-chevron-up').addClass('bi-chevron-down');

        faqItem.toggleClass('active');

        if (faqItem.hasClass('active')) {
            icon.removeClass('bi-chevron-down').addClass('bi-chevron-up');
        } else {
            icon.removeClass('bi-chevron-up').addClass('bi-chevron-down');
        }
    });

$(document).ready(function() {

    // ============================================
    // SEARCHABLE SELECT - Toggle Dropdown
    // ============================================
    $(document).on('click', '.searchable-select-display', function(e) {
        e.stopPropagation();
        var $select = $(this).closest('.searchable-select');
        
        // Close all other dropdowns
        $('.searchable-select').not($select).removeClass('active');
        
        // Toggle this dropdown
        $select.toggleClass('active');
        
        // Focus the search input if opened
        if ($select.hasClass('active')) {
            $select.find('.search-input').focus();
        }
    });

    // ============================================
    // SEARCHABLE SELECT - Search Filter
    // ============================================
    $(document).on('keyup', '.searchable-select .search-input', function() {
        var searchTerm = $(this).val().toLowerCase();
        var $options = $(this).closest('.searchable-select-dropdown').find('.options-list .option-item');
        
        $options.each(function() {
            var text = $(this).text().toLowerCase();
            if (text.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // ============================================
    // SEARCHABLE SELECT - Select Option
    // ============================================
    $(document).on('click', '.option-item', function() {
        var value = $(this).data('value');
        var townName = $(this).find('.town-name').text();
        var county = $(this).find('.town-county').text();
        
        var $container = $(this).closest('.searchable-select');
        var $hiddenInput = $container.find('input[type="hidden"]');
        var $displayText = $container.find('.selected-text');
        
        // Update display with town name and county
        $displayText.text(townName + ' (' + county + ')');
        
        // Set the hidden input value
        $hiddenInput.val(value);
        
        // Mark as selected
        $container.find('.option-item').removeClass('selected');
        $(this).addClass('selected');
        
        // Close dropdown
        $container.removeClass('active');
        
        // Clear search
        $container.find('.search-input').val('');
        $container.find('.options-list .option-item').show();
    });

    // ============================================
    // SEARCHABLE SELECT - Close on outside click
    // ============================================
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.searchable-select').length) {
            $('.searchable-select').removeClass('active');
        }
    });

});

    // ============================================
    // WHATSAPP TRACKING
    // ============================================
    $(document).on('click', '.whatsapp-button', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'whatsapp_click', {
                'event_category': 'engagement',
                'event_label': 'whatsapp_chat'
            });
        }

        $.ajax({
            url: '/track-whatsapp-click',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                page: window.location.pathname,
                timestamp: new Date().toISOString()
            }
        }).catch(function(error) {
            console.error('Error tracking WhatsApp click:', error);
        });
    });

});

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
 * Format a date string to a readable format
 */
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString('en-KE', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

/**
 * Show an alert message
 */
function showAlert(message, type = 'danger', container = '#alertContainer') {
    const alertTypes = {
        success: 'alert-success',
        danger: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info'
    };

    const alertClass = alertTypes[type] || 'alert-danger';
    const iconMap = {
        success: 'bi-check-circle-fill',
        danger: 'bi-exclamation-triangle-fill',
        warning: 'bi-exclamation-triangle',
        info: 'bi-info-circle-fill'
    };

    const alert = $(
        `<div class="alert ${alertClass} alert-dismissible fade show py-2" role="alert">
            <i class="bi ${iconMap[type] || 'bi-info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>`
    );

    $(container).html(alert).slideDown();

    setTimeout(() => {
        alert.alert('close');
    }, 5000);
}