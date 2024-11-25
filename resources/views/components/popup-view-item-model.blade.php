<!-- The Modal -->
<div id="popupViewItemModal" class="custom-modal {{$popupClasses}}">
    <div class="modal-content-wraper">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{$title}}</h2>
                <span class="popupCloseModel close-model">&times;</span>
            </div>
            <div class="modal-body space-y-4">                
                {{ $slot }}                
            </div>
        </div>
    </div>
</div>
