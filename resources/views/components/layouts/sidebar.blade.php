<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('logo.jpeg') }}" alt="{{ __('Karibu Parcels') }}"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ __('Karibu Parcels') }}</span>
    </a>

    @php
    $admin = Auth::guard('admin')->user();
    @endphp

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
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if($admin->can('parcel.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.parcels.index') }}" class="nav-link {{ request()->routeIs('admin.parcels*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Parcels</p>
                    </a>
                </li>
                @endif


                @if($admin?->hasAnyPermission(['partner.view', 'partner.create', 'partner.update', 'partner.delete']))
                <li class="nav-item">
                    <a href="{{ route('admin.partners.index') }}"
                        class="nav-link {{ request()->routeIs('admin.partners*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Partners</p>
                    </a>
                </li>
                @endif

                @if($admin->can('parcel-handling-assistant.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.pha.index') }}" class="nav-link {{ request()->routeIs('admin.parcel-handling*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Parcel Handling Ass.</p>
                    </a>
                </li>
                @endif

                @if($admin->can('pickup-and-dropoff-point.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.points.index') }}" class="nav-link {{ request()->routeIs('admin.stations*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>PickUp & DropOff Points</p>
                    </a>
                </li>
                @endif

                @if($admin->can('driver.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.drivers.index') }}" class="nav-link {{ request()->routeIs('admin.drivers*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Drivers</p>
                    </a>
                </li>
                @endif

                @if($admin->can('fleet.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.fleets.index') }}" class="nav-link {{ request()->routeIs('admin.fleets*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Fleets</p>
                    </a>
                </li>
                @endif

                @if($admin->can('payment.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Payments</p>
                    </a>
                </li>
                @endcan

                @if($admin->can('payment.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.payouts.index') }}" class="nav-link {{ request()->routeIs('admin.payouts*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Payouts</p>
                    </a>
                </li>
                @endcan

                @if($admin->can('user.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Users</p>
                    </a>
                </li>
                @endif


                @if($admin->can('roles.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.roles-and-permissions.index') }}" class="nav-link {{ request()->routeIs('admin.roles-and-permissions*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Role And Permissions</p>
                    </a>
                </li>
                @endif

                @if($admin->can('reports'))
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Reports</p>
                    </a>
                </li>
                @endif

                @if($admin?->hasAnyPermission(['blog-posts.view', 'blog-categories.view', 'blog-tags.view']))
                <li class="nav-item {{ request()->routeIs('admin.blog*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            Blogs
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->routeIs('admin.blog*') ? 'display: block;' : '' }}">
                        @if($admin->can('blog-posts.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.blog-posts.index') }}"
                                class="nav-link {{ request()->routeIs('admin.blog-posts*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Blog Posts</p>
                            </a>
                        </li>
                        @endif
                        @if($admin->can('blog-categories.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.blog-categories.index') }}"
                                class="nav-link {{ request()->routeIs('admin.blog-categories*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Blog Categories</p>
                            </a>
                        </li>
                        @endif
                        @if($admin->can('blog-tags.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.blog-tags.index') }}"
                                class="nav-link {{ request()->routeIs('admin.blog-tags*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Blog Tags</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if($admin?->hasAnyPermission(['items.view', 'weight-ranges.view', 'pricing.view', 'payment-structure.view', 'towns.view', 'zones.view', 'faqs.view']))
                <li class="nav-item {{ request()->routeIs('admin.categories*') || request()->routeIs('admin.sub-categories*') || request()->routeIs('admin.items*') || request()->routeIs('admin.weight-ranges*') || request()->routeIs('admin.pricing*') || request()->routeIs('admin.payment-structure*') || request()->routeIs('admin.towns*') || request()->routeIs('admin.zones*') || request()->routeIs('admin.faqs*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.categories*') || request()->routeIs('admin.sub-categories*') || request()->routeIs('admin.items*') || request()->routeIs('admin.weight-ranges*') || request()->routeIs('admin.pricing*') || request()->routeIs('admin.payment-structure*') || request()->routeIs('admin.towns*') || request()->routeIs('admin.zones*') || request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->routeIs('admin.categories*') || request()->routeIs('admin.sub-categories*') || request()->routeIs('admin.items*') || request()->routeIs('admin.weight-ranges*') || request()->routeIs('admin.pricing*') || request()->routeIs('admin.payment-structure*') || request()->routeIs('admin.towns*') || request()->routeIs('admin.zones*') || request()->routeIs('admin.faqs*') ? 'display: block;' : '' }}">

                        @if($admin->can('items.view'))
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
                                    <a href="{{ route('admin.items.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.items*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon text-danger"></i>
                                        <p>Items</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if($admin->can('weight-ranges.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.weight-ranges.index') }}"
                                class="nav-link {{ request()->routeIs('admin.weight-ranges*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Weight Ranges</p>
                            </a>
                        </li>
                        @endif

                        @if($admin->can('pricing.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.pricing.index') }}"
                                class="nav-link {{ request()->routeIs('admin.pricing*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pricings</p>
                            </a>
                        </li>
                        @endif

                        @if($admin->can('payment-structure.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.payment-structure.index') }}"
                                class="nav-link {{ request()->routeIs('admin.payment-structure*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Payment Structure</p>
                            </a>
                        </li>
                        @endif

                        @if($admin->can('towns.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.towns.index') }}"
                                class="nav-link {{ request()->routeIs('admin.towns*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Towns</p>
                            </a>
                        </li>
                        @endif



                        @if($admin->can('zones.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.zones.index') }}"
                                class="nav-link {{ request()->routeIs('admin.zones*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Zones</p>
                            </a>
                        </li>
                        @endif

                        @if($admin->can('faqs.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.faqs.index') }}"
                                class="nav-link {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>FAQs</p>
                            </a>
                        </li>
                        @endif

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
                @endif
            </ul>
        </nav>

    </div>
</aside>