<?php

namespace App\Livewire\Admin\Settings\Terms;

use Livewire\Component;
use App\Models\TermsAndCondition;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CreateTermsAndConditions extends Component
{
    public $title;
    public $version;
    public $content;
    public $locale = 'en';
    public $effective_date;
    public $expiry_date;
    public $requires_acceptance = true;
    public $is_active = false;
    
    // Legal specific fields
    public $introduction;
    public $definitions;
    public $user_obligations;
    public $liability_limitations;
    public $governing_law = 'United States';
    public $jurisdiction = 'New York';
    public $dispute_resolution = 'Arbitration';
    public $contact_email;
    public $contact_phone;
    public $company_name;
    public $company_address;
    
    // SEO fields
    public $meta_title;
    public $meta_description;
    
    // UI state
    public $showPreview = false;
    public $activeTab = 'content'; // content, legal, seo, preview
    public $slug;

    protected function rules()
    {
        return [
            'title' => 'required|min:3|max:255',
            'version' => 'required|max:50',
            'content' => 'required_if:activeTab,content|string',
            'locale' => 'required|in:en,es,fr,de,it,pt,ja,zh',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'requires_acceptance' => 'boolean',
            'is_active' => 'boolean',
            
            // Legal fields
            'introduction' => 'nullable|string',
            'definitions' => 'nullable|string',
            'user_obligations' => 'nullable|string',
            'liability_limitations' => 'nullable|string',
            'governing_law' => 'nullable|string|max:100',
            'jurisdiction' => 'nullable|string|max:100',
            'dispute_resolution' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:500',
            
            // SEO fields
            'meta_title' => 'nullable|max:70',
            'meta_description' => 'nullable|max:160',
        ];
    }

    protected $messages = [
        'title.required' => 'Please provide a title for these terms.',
        'version.required' => 'Version number is required.',
        'content.required_if' => 'Please enter the terms content.',
        'expiry_date.after' => 'Expiry date must be after the effective date.',
    ];

    public function mount()
    {
        $this->generateVersion();
        $this->generateMetaFields();
        $this->setCompanyInfo();
    }

    public function generateVersion()
    {
        $this->version = 'v' . date('Y.m.d') . '.1';
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function generateMetaFields()
    {
        if ($this->title) {
            $this->meta_title = $this->title . ' - Terms of Service';
            $this->meta_description = 'Read our ' . $this->title . '. Learn about your rights, obligations, and the terms governing your use of our services.';
        }
    }

    public function setCompanyInfo()
    {
        // You can load these from settings/config
        $this->company_name = config('app.name');
        $this->contact_email = config('app.contact_email', 'legal@' . config('app.domain'));
        $this->contact_phone = config('app.contact_phone');
        $this->company_address = config('app.company_address');
    }

    public function updatedTitle()
    {
        $this->generateMetaFields();
    }

    public function updatedLocale()
    {
        // Load template based on locale if needed
        $this->loadLocaleTemplate();
    }

    public function loadLocaleTemplate()
    {
        // You can implement logic to load language-specific templates
        // For now, just a placeholder
        session()->flash('info', 'Loaded template for ' . strtoupper($this->locale));
    }

    public function loadTemplate($template)
    {
        $templates = [
            'standard' => [
                'introduction' => "These Terms and Conditions ('Terms') govern your use of our website and services.",
                'definitions' => "• 'Service' refers to our website and related services\n• 'User' refers to anyone accessing our service\n• 'Content' refers to any material uploaded or shared",
                'user_obligations' => "Users must:\n- Provide accurate information\n- Maintain account security\n- Comply with all applicable laws\n- Not engage in prohibited activities",
                'liability_limitations' => "To the maximum extent permitted by law, we shall not be liable for any indirect, incidental, or consequential damages.",
            ],
            'ecommerce' => [
                'introduction' => "These Terms govern all sales and purchases made through our platform.",
                'definitions' => "• 'Products' refers to items available for purchase\n• 'Buyer' refers to customers purchasing products\n• 'Seller' refers to the company providing products",
                'user_obligations' => "Buyers must:\n- Provide valid payment information\n- Accept delivery terms\n- Comply with return policies",
                'liability_limitations' => "Liability is limited to the purchase price of products. We are not liable for shipping delays or product availability.",
            ],
            'saas' => [
                'introduction' => "These Terms govern your subscription and use of our software service.",
                'definitions' => "• 'Software' refers to our SaaS platform\n• 'Subscription' refers to paid access plans\n• 'Data' refers to information processed through the service",
                'user_obligations' => "Subscribers must:\n- Pay subscription fees on time\n- Not exceed usage limits\n- Maintain data confidentiality\n- Not reverse engineer the software",
                'liability_limitations' => "Liability is limited to subscription fees paid. We are not liable for data loss or service interruptions.",
            ],
        ];

        if (isset($templates[$template])) {
            $this->introduction = $templates[$template]['introduction'];
            $this->definitions = $templates[$template]['definitions'];
            $this->user_obligations = $templates[$template]['user_obligations'];
            $this->liability_limitations = $templates[$template]['liability_limitations'];
            
            session()->flash('success', ucfirst($template) . ' template loaded successfully!');
        }
    }

    public function assembleContent()
    {
        // Build the full content from sections
        $content = "";
        
        if ($this->introduction) {
            $content .= "## INTRODUCTION\n\n" . $this->introduction . "\n\n";
        }
        
        if ($this->definitions) {
            $content .= "## DEFINITIONS\n\n" . $this->definitions . "\n\n";
        }
        
        if ($this->user_obligations) {
            $content .= "## USER OBLIGATIONS\n\n" . $this->user_obligations . "\n\n";
        }
        
        if ($this->liability_limitations) {
            $content .= "## LIMITATION OF LIABILITY\n\n" . $this->liability_limitations . "\n\n";
        }
        
        // Add governing law
        $content .= "## GOVERNING LAW\n\n";
        $content .= "These Terms shall be governed by the laws of {$this->governing_law}, ";
        $content .= "without regard to its conflict of law provisions. ";
        $content .= "Any disputes shall be resolved in the courts of {$this->jurisdiction}.\n\n";
        
        // Add dispute resolution
        if ($this->dispute_resolution) {
            $content .= "## DISPUTE RESOLUTION\n\n";
            $content .= "Any disputes arising from these Terms shall be resolved through ";
            $content .= "{$this->dispute_resolution}.\n\n";
        }
        
        // Add contact information
        $content .= "## CONTACT US\n\n";
        $content .= "If you have any questions about these Terms, please contact us at:\n\n";
        $content .= "**{$this->company_name}**\n";
        
        if ($this->company_address) {
            $content .= "Address: {$this->company_address}\n";
        }
        
        if ($this->contact_email) {
            $content .= "Email: {$this->contact_email}\n";
        }
        
        if ($this->contact_phone) {
            $content .= "Phone: {$this->contact_phone}\n";
        }
        
        return $content;
    }

    public function save()
    {
        $this->validate();

        // Assemble full content if using sections
        if ($this->activeTab !== 'content' && !$this->content) {
            $this->content = $this->assembleContent();
        }

        $terms = TermsAndCondition::create([
            'title' => $this->title,
            'version' => $this->version,
            'content' => $this->content,
            'locale' => $this->locale,
            'effective_date' => $this->effective_date,
            'expiry_date' => $this->expiry_date,
            'requires_acceptance' => $this->requires_acceptance,
            'is_active' => $this->is_active,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        session()->flash('success', 'Terms and Conditions created successfully!');

        if ($this->is_active) {
            return redirect()->route('admin.terms-and-conditions.index');
        }

        return redirect()->route('admin.terms-and-conditions.edit', $terms->id);
    }

    public function togglePreview()
    {
        $this->showPreview = !$this->showPreview;
        if ($this->showPreview && !$this->content) {
            $this->content = $this->assembleContent();
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.terms.create-terms-and-conditions');
    }
}