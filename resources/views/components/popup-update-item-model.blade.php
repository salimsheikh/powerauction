<!-- The Modal -->
<div id="popupUpdateItemModal" class="custom-modal">
    <div class="modal-content-wraper">
        <!-- Modal content -->
        <div class="modal-content max-w-lg">
            <div class="modal-header">
                <h2>{{$title}}</h2>
                <span class="popupCloseModel close-model">&times;</span>
            </div>
            <div class="modal-body space-y-4">
                <form id="popupUpdateForm" method="post" class="space-y-4">
                    <div class="alert alert-info model-body-alert"></div>
                    {{ $slot }}
                    <div class="modal-body-footer">
                        <x-button-popup-close>
                            {{ __('Close') }}
                        </x-button-popup-close>

                        <x-button-popup-update class="ms-3">
                            {{ __('Update') }}
                        </x-button-popup-update>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
