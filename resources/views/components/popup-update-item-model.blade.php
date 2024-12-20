<!-- The Modal -->
<div id="popupUpdateItemModal" class="custom-modal {{$popupClasses}}">
    <div class="modal-content-wraper">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{$title}}</h2>
                <span class="popupCloseModel close-model">&times;</span>
            </div>
            <div class="modal-body space-y-4">
                <form id="popupUpdateForm" method="post" class="space-y-4"  enctype="multipart/form-data">
                    <input type="hidden" id="update_id" name="update_id" value="0" />
                    <div class="alert alert-info model-body-alert"></div>
                    <div class="popup-fields">
                        {{ $slot }}
                    </div>
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
