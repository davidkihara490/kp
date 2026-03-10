<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Edit Zone</h3>
        </div>
        <div class="card-body">
            
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="update">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Zone Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   wire:model="name" placeholder="Enter zone name">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select Counties <span class="text-danger">*</span></label>
                            <div class="border p-3 rounded" style="max-height: 400px; overflow-y: auto;">
                                <div class="row">
                                    @forelse ($counties as $county)
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       class="form-check-input @error('selectedCounties') is-invalid @enderror" 
                                                       wire:model="selectedCounties" 
                                                       value="{{ $county->id }}"
                                                       id="county_{{ $county->id }}">
                                                <label class="form-check-label" for="county_{{ $county->id }}">
                                                    {{ $county->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted mb-0">No counties found.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            @error('selectedCounties')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('selectedCounties.*')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary" wire:click="update" wire:loading.attr="disabled"
                    wire:target="update">
                <span wire:loading.remove wire:target="update">
                    <i class="fas fa-save"></i> Update
                </span>
                <span wire:loading wire:target="update">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Updating...
                </span>
            </button>
            <a href="{{ route('admin.zones.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </div>
</div>