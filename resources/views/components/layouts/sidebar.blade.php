<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('logo.jpeg') }}" alt="{{ __('Karibu Parcels') }}"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ __('Karibu Parcels') }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('logo.jpeg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ __('Karibu Parcels') }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                
                {{-- All your existing navigation items here --}}
                
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Parcels --}}
                <li class="nav-item">
                    <a href="{{ route('admin.parcels.index') }}" class="nav-link {{ request()->routeIs('admin.parcels*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Parcels
                        </p>
                    </a>
                </li>

                {{-- Partners --}}
                <li class="nav-item">
                    <a href="{{ route('admin.partners.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.partners*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Partners</p>
                    </a>
                </li>

                {{-- Parcel Handling Ass. --}}
                <li class="nav-item">
                    <a href="{{ route('admin.pha.index') }}" class="nav-link {{ request()->routeIs('admin.parcel-handling*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Parcel Handling Ass.</p>
                    </a>
                </li>

                {{-- Stations --}}
                <li class="nav-item">
                    <a href="{{ route('admin.points.index') }}" class="nav-link {{ request()->routeIs('admin.stations*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>PickUp & DropOff Points</p>
                    </a>
                </li>

                {{-- Drivers --}}
                <li class="nav-item">
                    <a href="{{ route('admin.drivers.index') }}" class="nav-link {{ request()->routeIs('admin.drivers*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Drivers</p>
                    </a>
                </li>

                {{-- Fleets --}}
                <li class="nav-item">
                    <a href="{{ route('admin.fleets.index') }}" class="nav-link {{ request()->routeIs('admin.fleets*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Fleets</p>
                    </a>
                </li>

                {{-- Payments --}}
                <li class="nav-item">
                    <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Payments</p>
                    </a>
                </li>

                {{-- Reports --}}
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Reports</p>
                    </a>
                </li>

                {{-- Blogs with Submenu --}}
                <li class="nav-item {{ request()->routeIs('admin.blog*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            Blogs
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->routeIs('admin.blog*') ? 'display: block;' : '' }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.blog-posts.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.blog-posts*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Blog Posts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blog-categories.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.blog-categories*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Blog Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blog-tags.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.blog-tags*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Blog Tags</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Settings with Submenu --}}
                <li class="nav-item {{ request()->routeIs('admin.categories*') || request()->routeIs('admin.sub-categories*') || request()->routeIs('admin.items*') || request()->routeIs('admin.weight-ranges*') || request()->routeIs('admin.pricing*') || request()->routeIs('admin.payment-structure*') || request()->routeIs('admin.towns*') || request()->routeIs('admin.zones*') || request()->routeIs('admin.faqs*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.categories*') || request()->routeIs('admin.sub-categories*') || request()->routeIs('admin.items*') || request()->routeIs('admin.weight-ranges*') || request()->routeIs('admin.pricing*') || request()->routeIs('admin.payment-structure*') || request()->routeIs('admin.towns*') || request()->routeIs('admin.zones*') || request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->routeIs('admin.categories*') || request()->routeIs('admin.sub-categories*') || request()->routeIs('admin.items*') || request()->routeIs('admin.weight-ranges*') || request()->routeIs('admin.pricing*') || request()->routeIs('admin.payment-structure*') || request()->routeIs('admin.towns*') || request()->routeIs('admin.zones*') || request()->routeIs('admin.faqs*') ? 'display: block;' : '' }}">
                        
                        {{-- Items Submenu --}}
                        <li class="nav-item {{ request()->routeIs('admin.categories*') || request()->routeIs('admin.sub-categories*') || request()->routeIs('admin.items*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Items
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon text-danger"></i>
                                        <p>Categories</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.sub-categories.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.sub-categories*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon text-danger"></i>
                                        <p>Sub Categories</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.items.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.items*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon text-danger"></i>
                                        <p>Items</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Other Settings --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.weight-ranges.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.weight-ranges*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Weight Ranges</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.pricing.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.pricing*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pricings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.payment-structure.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.payment-structure*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Payment Structure</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.towns.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.towns*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Towns</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.zones.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.zones*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Zones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.faqs.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>FAQs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.terms') }}" 
                               class="nav-link {{ request()->routeIs('admin.terms*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Terms And Conditions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.policy') }}" 
                               class="nav-link {{ request()->routeIs('admin.policy*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Privacy Policy</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        
        {{-- Logout Button Section --}}
        <div class="sidebar-bottom mt-auto p-3" style="position: absolute; bottom: 0; width: 100%;">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent" 
                                style="cursor: pointer; color: #c2c7d0;" 
                                onmouseover="this.style.backgroundColor='rgba(255,255,255,.1)'" 
                                onmouseout="this.style.backgroundColor='transparent'">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>{{ __('Logout') }}</p>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</aside>

{{-- Optional: Add some CSS to ensure proper positioning --}}
<style>
    .sidebar {
        position: relative;
        min-height: calc(100vh - 3.5rem);
        padding-bottom: 70px !important;
    }
    
    .sidebar-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #343a40;
        border-top: 1px solid #4b545c;
    }
    
    .sidebar-bottom .nav-link {
        padding: 0.8rem 1rem;
        border-radius: 0;
    }
    
    .sidebar-bottom .nav-link:hover {
        background-color: rgba(255,255,255,.1);
    }
    
    .sidebar-bottom button.nav-link {
        color: #c2c7d0 !important;
        text-align: left !important;
        width: 100%;
    }
    
    .sidebar-bottom button.nav-link:hover {
        color: #fff !important;
    }
</style>