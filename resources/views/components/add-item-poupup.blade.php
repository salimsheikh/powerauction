<div>

    <!-- Trigger/Open The Modal -->
    <button id="myBtn">Open Modal</button>

    <!-- The Modal -->
    <div id="myModal" class="custom-modal">
        <div class="modal-content-wraper">

            <!-- Modal content -->
            <div class="modal-content max-w-lg">
                <div class="modal-header">
                    <h2>Add New Item</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body space-y-4">
                    <form id="popupAddForm" method="post" class="space-y-4">
                        <div class="alert alert-info model-body-alert">
                            
                        </div>
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

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        btn.onclick();

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</div>
