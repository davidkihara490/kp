<?php

namespace Database\Seeders;

use App\Models\FAQ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            ['question' => 'What are your delivery times?', 'answer' => 'We deliver within 2-5 business days depending on location.', 'status' => 1],
            ['question' => 'Do you offer international shipping?', 'answer' => 'Yes, we ship to over 150 countries worldwide.', 'status' => 1],
            ['question' => 'How can I track my package?', 'answer' => 'You can track your package using the tracking number sent via email.', 'status' => 1],
            ['question' => 'What is your return policy?', 'answer' => 'We offer 30-day returns for unopened items in original condition.', 'status' => 1],
            ['question' => 'Do you offer same-day delivery?', 'answer' => 'Same-day delivery is available in selected urban areas for orders placed before 10 AM.', 'status' => 1],
            ['question' => 'How much does shipping cost?', 'answer' => 'Shipping costs vary by weight and destination. Calculate at checkout for exact rates.', 'status' => 1],
            ['question' => 'Can I change my delivery address?', 'answer' => 'You can modify the address within 24 hours of placing your order.', 'status' => 1],
            ['question' => 'What payment methods do you accept?', 'answer' => 'We accept credit cards, debit cards, bank transfers, and digital wallets.', 'status' => 1],
            ['question' => 'Is my package insured?', 'answer' => 'All packages are insured up to $500. Additional coverage is available upon request.', 'status' => 1],
            ['question' => 'How do I contact customer support?', 'answer' => 'Reach us via email, phone, or live chat available 24/7 on our website.', 'status' => 1],
        ];

        foreach($faqs as $faq){
            FAQ::create($faq);
        }
    }
}
