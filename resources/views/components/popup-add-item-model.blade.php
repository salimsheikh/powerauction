<!-- The Modal -->
<div id="popupAddItemModal" class="custom-modal">
    <div class="modal-content-wraper">
        <!-- Modal content -->
        <div class="modal-content max-w-lg">
            <div class="modal-header">
                <h2>{{ __('Add New Item') }}</h2>
                <span class="popupCloseModel close-model">&times;</span>
            </div>
            <div class="modal-body space-y-4">
                <form id="popupAddForm" method="post" class="space-y-4">
                    <div class="alert alert-info model-body-alert"></div>
                    {{ $slot }}
                    <div class="modal-body-footer">
                        <x-button-popup-close>
                            {{ __('Close') }}
                        </x-button-popup-close>

                        <x-button-popup-add class="ms-3">
                            {{ __('Add Item') }}
                        </x-button-popup-add>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
