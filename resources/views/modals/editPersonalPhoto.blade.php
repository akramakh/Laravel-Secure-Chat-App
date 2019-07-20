<div class="modal-content">
    <form method="post" action="user/update-personal-photo" id="upload_form" accept-charset="UTF-8" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Personal Photo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        {{csrf_field()}}
            <div class="form-group row">
                <label for="personal_photo" class="col-md-2">Photo</label>
                <div class="col-md-8 input-btn" id="personal_photo">
                    <i class="fa fa-upload fa-fw"></i><strong> Upload Your Photo</strong>
                <input type="file" name="personal_photo" onchange="imagePreview(this);">
                </div>

                <div class="profile-img-container" style="width:100%;">

                    <img src="" id="profile_img"  style="display:none;width: 100%;object-fit: cover;object-position: center;"/>
                </div>
            </div>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="add-photo-btn" name="add" disabled ><i class="fa fa-plus fa-fw"></i>Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancle</button>
      </div>
    </form>
</div>