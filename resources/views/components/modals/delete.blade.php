      <div class="modal fade" id="modal-overlay">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="overlay">
                      <i class="fas fa-2x fa-sync fa-spin"></i>
                  </div>
                  <div class="modal-header">
                      <h4 class="modal-title">Confirm Delete</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <p>Are you sure you want to delete this item?</p>
                  </div>
                  <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-danger" wire:click="deleteItem"
                          wire:loading.attr="disabled">
                          <span wire:loading.remove wire:target="deleteItem">Delete</span>
                          <span wire:loading wire:target="deleteItem">Deleting...</span>
                      </button>
                  </div>
              </div>
          </div>
      </div>
