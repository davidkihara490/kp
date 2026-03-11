<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::domain('admin.karibuparcels.com')->as('admin.')->group(function () {
    Route::get('/login', function () {
        return view('pages.admin.auth.login');
    })->name('login');

Route::middleware(['admin.auth'])->group(function(){
        Route::get('/dashboard', function () {
        return view('pages.admin.dashboard.dashboard');
    })->name('dashboard');
    Route::prefix('parcels')->group(function () {
        Route::get('/', function () {
            return view('pages.admin.parcels.index');
        })->name('parcels.index');
        Route::get('/create', function () {
            return view('pages.admin.parcels.create');
        })->name('parcels.create');
        Route::get('/edit/{id}', function ($id) {
            return view('pages.admin.parcels.edit', compact('id'));
        })->name('parcels.edit');
        Route::get('/view/{id}', function ($id) {
            return view('pages.admin.parcels.view', compact('id'));
        })->name('parcels.view');
    });
    Route::prefix('partners')->group(function () {
        Route::get('/', function () {
            return view('pages.admin.partners.index');
        })->name('partners.index');
        Route::get('/create', function () {
            return view('pages.admin.partners.create');
        })->name('partners.create');
        Route::get('/edit/{id}', function ($id) {
            return view('pages.admin.partners.edit', compact('id'));
        })->name('partners.edit');
        Route::get('/view/{id}', function ($id) {
            return view('pages.admin.partners.view', compact('id'));
        })->name('partners.view');
    });
    Route::prefix('parcel-handling-assistants')->group(function () {
        Route::get('/', function () {
            return view('pages.admin.parcel-handling-assistants.index');
        })->name('pha.index');
        Route::get('/create', function () {
            return view('pages.admin.parcel-handling-assistants.create');
        })->name('pha.create');
        Route::get('/edit/{id}', function ($id) {
            return view('pages.admin.parcel-handling-assistants.edit', compact('id'));
        })->name('pha.edit');
        Route::get('/view/{id}', function ($id) {
            return view('pages.admin.parcel-handling-assistants.view', compact('id'));
        })->name('pha.view');
    });
    Route::prefix('points')->group(function () {
        Route::get('/', function () {
            return view('pages.admin.points.index');
        })->name('points.index');
        Route::get('/create', function () {
            return view('pages.admin.points.create');
        })->name('points.create');
        Route::get('/edit/{id}', function ($id) {
            return view('pages.admin.points.edit', compact('id'));
        })->name('points.edit');
        Route::get('/view/{id}', function ($id) {
            return view('pages.admin.points.view', compact('id'));
        })->name('points.view');
    });
    Route::prefix('drivers')->group(function () {
        Route::get('/', function () {
            return view('pages.admin.drivers.index');
        })->name('drivers.index');
        Route::get('/create', function () {
            return view('pages.admin.drivers.create');
        })->name('drivers.create');
        Route::get('/edit/{id}', function ($id) {
            return view('pages.admin.drivers.edit', compact('id'));
        })->name('drivers.edit');
        Route::get('/view/{id}', function ($id) {
            return view('pages.admin.drivers.view', compact('id'));
        })->name('drivers.view');
    });
    Route::prefix('fleets')->group(function () {
        Route::get('/', function () {
            return view('pages.admin.fleets.index');
        })->name('fleets.index');
        Route::get('/create', function () {
            return view('pages.admin.fleets.create');
        })->name('fleets.create');
        Route::get('/edit/{id}', function ($id) {
            return view('pages.admin.fleets.edit', compact('id'));
        })->name('fleets.edit');
        Route::get('/view/{id}', function ($id) {
            return view('pages.admin.fleets.view', compact('id'));
        })->name('fleets.view');
    });
    Route::prefix('payments')->group(function () {
        Route::get('/', function () {
            return view('pages.admin.payments.index');
        })->name('payments.index');
    });
    Route::prefix('settings')->group(function () {
        Route::prefix('towns')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.towns.index');
            })->name('towns.index');

            Route::get('/create', function () {
                return view('pages.admin.settings.towns.create');
            })->name('towns.create');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.towns.edit', compact('id'));
            })->name('towns.edit');
        });

        Route::prefix('zones')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.zones.index');
            })->name('zones.index');

            Route::get('/create', function () {
                return view('pages.admin.settings.zones.create');
            })->name('zones.create');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.zones.edit', compact('id'));
            })->name('zones.edit');
        });

        Route::prefix('pricing')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.pricing.index');
            })->name('pricing.index');

            Route::get('/create', function () {
                return view('pages.admin.settings.pricing.create');
            })->name('pricing.create');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.pricing.edit', compact('id'));
            })->name('pricing.edit');

            Route::get('/view/{id}', function ($id) {
                return view('pages.admin.settings.pricing.view', compact('id'));
            })->name('pricing.view');
        });

        Route::prefix('payment-structure')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.payment-structure.index');
            })->name('payment-structure.index');
            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.payment-structure.edit', compact('id'));
            })->name('payment-structure.edit');
        });

        Route::prefix('faqs')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.faqs.index');
            })->name('faqs.index');

            Route::get('/create', function () {
                return view('pages.admin.settings.faqs.create');
            })->name('faqs.create');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.faqs.edit', compact('id'));
            })->name('faqs.edit');
        });

        Route::prefix('categories')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.categories.index');
            })->name('categories.index');

            Route::get('/create', function () {
                return view('pages.admin.settings.categories.create');
            })->name('categories.create');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.categories.edit', compact('id'));
            })->name('categories.edit');
        });


        Route::prefix('sub-categories')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.sub-categories.index');
            })->name('sub-categories.index');

            Route::get('/create', function () {
                return view('pages.admin.settings.sub-categories.create');
            })->name('sub-categories.create');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.sub-categories.edit', compact('id'));
            })->name('sub-categories.edit');
        });

        Route::prefix('items')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.items.index');
            })->name('items.index');

            Route::get('/create', function () {
                return view('pages.admin.settings.items.create');
            })->name('items.create');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.items.edit', compact('id'));
            })->name('items.edit');
        });

        Route::prefix('weight-ranges')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.weight-ranges.index');
            })->name('weight-ranges.index');

            Route::get('/create', function () {
                return view('pages.admin.settings.weight-ranges.create');
            })->name('weight-ranges.create');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.weight-ranges.edit', compact('id'));
            })->name('weight-ranges.edit');
        });

        Route::prefix('terms-and-conditions')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.terms.terms');
            })->name('terms');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.terms.edit', compact('id'));
            })->name('terms.edit');

            Route::get('/create', function () {
                return view('pages.admin.settings.terms.create');
            })->name('terms.create');

            Route::get('/view/{id}', function ($id) {
                return view('pages.admin.settings.terms.view', compact('id'));
            })->name('terms.view');
        });


        Route::prefix('policy')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.settings.policy.policy');
            })->name('policy');

            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.settings.policy.edit', compact('id'));
            })->name('policy.edit');
        });
    });
    Route::prefix('blogs')->group(function () {
        Route::prefix('blog-categories')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.blogs.categories.index');
            })->name('blog-categories.index');
            Route::get('/create', function () {
                return view('pages.admin.blogs.categories.create');
            })->name('blog-categories.create');
            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.blogs.categories.edit', compact('id'));
            })->name('blog-categories.edit');
            Route::get('/view/{id}', function ($id) {
                return view('pages.admin.blogs.categories.view', compact('id'));
            })->name('blog-categories.view');
        });

        Route::prefix('blogs')->group(function () {
            Route::get('/',  function () {
                return view('pages.admin.blogs.posts.index');
            })->name('blog-posts.index');
            Route::get('/create', function () {
                return view('pages.admin.blogs.posts.create');
            })->name('blog-posts.create');
            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.blogs.posts.edit', compact('id'));
            })->name('blog-posts.edit');
            Route::get('/view/{id}', function ($id) {
                return view('pages.admin.blogs.posts.view', compact('id'));
            })->name('blog-posts.view');
        });

        Route::prefix('tags')->group(function () {
            Route::get('/',  function () {
                return view('pages.admin.blogs.tags.index');
            })->name('blog-tags.index');
            Route::get('/create', function () {
                return view('pages.admin.blogs.tags.create');
            })->name('blog-tags.create');
            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin.blogs.tags.edit', compact('id'));
            })->name('blog-tags.edit');
            Route::get('/view/{id}', function ($id) {
                return view('pages.admin.blogs.tags.view', compact('id'));
            })->name('blog-tags.view');
        });
    });
    Route::post('logout', function () {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    })->name('logout');
});
});
