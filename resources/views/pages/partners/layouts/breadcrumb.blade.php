<div class="header-left">
    <button class="menu-toggle" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>
    <div class="page-title">
        <h1>
            @php
            $types = [
            'pha' => 'ParcelHandling Assistant',
            'driver' => 'Driver',
            'pickup-dropoff' => 'Pick-Up and Drop-Off',
            'transport' => 'Transport',
            ];

            $type = strtolower(trim($__env->yieldContent('user-type')));
            $label = $types[$type] ?? ucfirst($type);
            @endphp

            {{ $label }} Partner
        </h1>


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">@yield('page-title')</li>
            </ol>
        </nav>
    </div>
</div>