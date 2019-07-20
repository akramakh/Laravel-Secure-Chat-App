<style>
.form-group{
        display:contents;
    }
</style>    
<div class="modal-content" style="width:auto;">
      <div class="modal-header">
          <h4 class="modal-title" style="margin:0;">Edit User Info</h4>
        <button type="button" class="close" onclick="removeAlert()" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <div class="alert alert-success modal-alert" id="modal_alert" style="display:none;"></div> 
          <div class="">
            <form class="form-horizontal" role="modal">

              <div class="form-group row col-sm-12">
                <label class="control-label col-sm-2"for="user_id_in">ID</label>
                  <input type="text" class="form-control col-sm-10" id="user_id_in" value="{{$user->id}}" disabled>
              </div>

              <div class="form-group row col-sm-12">
                <label class="control-label col-sm-2"for="user_name_in">Name</label>
                <input type="name" class="form-control col-sm-10" id="user_name_in" value="{{$user->name}}" >
              </div>

              <div class="form-group row col-sm-12">
                <label class="control-label col-sm-2"for="user_email_in">Email</label>
                  <input type="email" class="form-control col-sm-10" id="user_email_in" value="{{$user->email}}" >
              </div>

            </form>
          </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary actionBtn" onclick="updateUser(this)">
          <span id="footer_action_button" class="glyphicon glyphicon-edit">Update</span>
        </button>

        <button type="button" class="btn btn-warning" onclick="removeAlert()" data-dismiss="modal">
          <span class="glyphicon glyphicon"></span>close
        </button>

      </div>
    </div>
