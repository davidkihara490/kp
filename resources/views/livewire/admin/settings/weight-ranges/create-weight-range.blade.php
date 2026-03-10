<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Create Weight Range</h3>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <form wire:submit.prevent="submit">
                <div class="row">

                    {{-- Min Weight --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Minimum Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control"
                                   wire:model="min_weight"
                                   placeholder="Enter minimum weight">
                            @error('min_weight')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Max Weight --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Maximum Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control"
                                   wire:model="max_weight"
                                   placeholder="Enter maximum weight">
                            @error('max_weight')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Label --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Label</label>
                            <input type="text" class="form-control"
                                   wire:model="label"
                                   placeholder="Example: 0 - 1 kg">
                            @error('label')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="mt-4">
                    <button type="submit"
                            class="btn btn-primary"
                            wire:loading.attr="disabled"
                            wire:target="submit">
                        <span wire:loading.remove wire:target="submit">Submit</span>
                        <span wire:loading wire:target="submit">
                            <span class="spinner-border spinner-border-sm"></span>
                            Saving...
                        </span>
                    </button>

                    <button type="button" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
