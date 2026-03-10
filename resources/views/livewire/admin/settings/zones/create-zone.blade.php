<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Create Zone</h3>
        </div>
        <div class="card-body">
            
            @include('components.alerts.response-alerts')

            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Zone Name</label>
                            <input type="text" class="form-control" wire:model="name" placeholder="Enter zone name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select Counties</label>
                            <div class="border p-3 rounded" style="max-height: 400px; overflow-y: auto;">
                                <div class="row">
                                    @foreach ($counties as $county)
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       class="form-check-input" 
                                                       wire:model="selectedCounties" 
                                                       value="{{ $county->id }}"
                                                       id="county_{{ $county->id }}">
                                                <label class="form-check-label" for="county_{{ $county->id }}">
                                                    {{ $county->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
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
            <button type="submit" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled"
                wire:target="save">
                <span wire:loading.remove wire:target="save">Submit</span>
                <span wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Submitting...
                </span>
            </button>
            <a href="{{ route('admin.zones.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</div>