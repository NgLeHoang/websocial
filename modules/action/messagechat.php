<div class="modal fade" id="messageinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <img src="assets/img/profile/default_image.jpg" alt="default_image" id="chatter_pic" height="40" width="40" class="m-1 border rounded-circle">
                    <span id="chatter_name"></span>
                    @<span id="chatter_username"></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex flex-column-reverse gap-2" id="userchat">
                
            </div>
            <div class="modal-footer">
                <div class="input-group p-2 border-top">
                    <input type="text" class="form-control rounded-0 border-0" id="messageinput" placeholder="Aa">
                    <button class="btn btn-outline-primary rounded-0 border-0" id="sendmessage" data-user-id="0" type="button">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>