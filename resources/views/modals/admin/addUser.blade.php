<style>
.form-group{
        display:contents;
    }
</style>
<div class="modal-content" style="width: auto;">
<form>
                    {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title"  style="margin:0;">Add User</h4>
                    <button type="button" class="close" onclick="removeAlert()" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success modal-alert" id="modal_alert" style="display:none;"></div>
                    <div class="form-group row col-md-12">
                        <label for="name" class="col-md-4">Name</label>
                        <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
                    </div>
                    <div class="form-group row col-md-12">
                        <label for="email" class="col-md-4">Email</label>
                        <input id="email" type="email" class="form-control" name="email" value="" required>
                    </div>
                    <div class="form-group row col-md-12">
                        <label for="password" class="col-md-4">Password</label>
                        <input id="password" type="password" class="form-control " name="password" required>
                    </div>
                    <div class="form-group row col-md-12">
                        <label for="password-confirm" class="col-md-4">confirm</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add_user" onclick="addUser(this);">
                    <i class="fa fa-plus"></i> Add
                </button>
                <button type="button" class="btn btn-defualt" onclick="removeAlert()" data-dismiss="modal">
                    <i class="fa fa-remove"></i> Cancle
                </button>
            </div>
            </form>
            
        </div>