<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Edit FAQ</h3>
        </div>
        <div class="card-body">


            @include('components.alerts.response-alerts')

            <form wire:submit.prevent="submit">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" class="form-control" wire:model="question" placeholder="Enter question">
                            @error('question')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Answer</label>
                            <textarea  class="form-control" wire:model="answer" id="" rows="5"></textarea>
                            @error('answer')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" wire:model="status" style="width: 100%;">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary" wire:click="submit" wire:loading.attr="disabled"
                wire:target="submit">
                <span wire:loading.remove wire:target="submit">Submit</span>
                <span wire:loading wire:target="submit">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Submitting...
                </span>
            </button>
            <button type="button" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</div>
