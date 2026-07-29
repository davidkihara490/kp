@if(isset($faqs) && $faqs->count() > 0)
<section id="faq" class="faq-section">
    <div class="container">
        <div class="section-title">
            <h2>Frequently Asked Questions</h2>
            <p class="text-muted">Find answers to common questions about our services</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                @foreach($faqs as $faq)
                <div class="faq-item">
                    <div class="faq-question">
                        <span>{{ $faq->question }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted">Still have questions? <a href="#contact" class="text-primary fw-bold">Contact us</a></p>
        </div>
    </div>
</section>
@endif