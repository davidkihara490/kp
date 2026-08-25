<!-- Preloader -->
<div id="preloader" class="preloader">
    <div class="preloader-spinner">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="mt-3 text-primary fw-semibold">{{ config('app.name') }}</div>
    </div>
</div>

<style>
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: white;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }

    .preloader.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .preloader-spinner {
        text-align: center;
    }

    .preloader-spinner .spinner-border {
        color: var(--primary-color);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            setTimeout(function() {
                preloader.classList.add('hidden');
            }, 500);
        }
    });
</script>