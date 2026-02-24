{{-- Modal Popup for Alert Messages--}}
<div class="modal fade" id="modal_popup" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="modal_title">Modal Title</h4>
            </div>
            <div class="modal-body" id="popup_content">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Popup for Confirm Messages --}}
<div class="modal fade" id="modal_popup_confirm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="modal_title_confirm">Modal Title</h4>
            </div>
            <div class="modal-body" id="popup_content_confirm">
            </div>
            <div class="modal-footer">
                <button id="confirm_yes" type="button" class="btn btn-success" data-dismiss="modal">Yes</button>
                <button id="confirm_no" type="button" class="btn default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>