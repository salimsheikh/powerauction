<!-- The Modal -->
<div id="popupDeleteItemModal" class="custom-modal">
    <div class="modal-content-wraper">
        <!-- Modal content -->
        <div class="modal-content max-w-lg">
            <div class="modal-header">
                <h2>{{ __('Delete Item') }}</h2>
                <span class="popupCloseModel close-model">&times;</span>
            </div>
            <div class="modal-body space-y-4">
                <form id="popupDeleteForm" method="post" class="space-y-4">
                    <div class="alert alert-info model-body-alert danger-alert"></div>
                    {{ $slot }}
                    <div class="modal-body-footer">
                        <x-button-popup-close>
                            {{ __('Cancel') }}
                        </x-button-popup-close>

                        <x-button-popup-delete class="ms-3">
                            {{ __('Delete Item') }}
                        </x-button-popup-delete>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
