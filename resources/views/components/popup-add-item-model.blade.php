<!-- The Modal -->
<div id="popupAddItemModal" class="custom-modal {{$popupClasses}}">
    <div class="modal-content-wraper">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{$title}}</h2>
                <span class="popupCloseModel close-model">&times;</span>
            </div>
            <div class="modal-body space-y-4">
                <form id="{{$formId}}" method="post" class="space-y-4">
                    <div class="alert alert-info model-body-alert"></div>
                    <div class="popup-fields">
                        {{ $slot }}
                    </div>
                    <div class="modal-body-footer">
                        <x-button-popup-close>
                            {{ __('Close') }}
                        </x-button-popup-close>

                        @if($formType == 'add')
                            <x-button-popup-add class="ms-3">{{$actionButtonLabel}}</x-button-popup-add>
                        @else
                            <x-button-popup-update class="ms-3">{{$actionButtonLabel}}</x-button-popup-add>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
