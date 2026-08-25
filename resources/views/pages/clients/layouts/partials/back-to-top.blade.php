<!-- Back to Top Button -->
<button id="backToTop" class="back-to-top" aria-label="Back to top">
    <i class="bi bi-chevron-up"></i>
</button>

<style>
    .back-to-top {
        position: fixed;
        bottom: 100px;
        right: 30px;
        z-index: 999;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 143, 64, 0.3);
        transition: var(--transition);
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        cursor: pointer;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .back-to-top:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 143, 64, 0.4);
        background: var(--primary-dark);
    }

    .back-to-top:active {
        transform: scale(0.95);
    }

    @media (max-width: 768px) {
        .back-to-top {
            bottom: 85px;
            right: 20px;
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopBtn = document.getElementById('backToTop');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });

        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>