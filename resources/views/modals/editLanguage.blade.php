    <div class="modal-content" style="width:auto;">
      <div class="modal-header">
          <h4 class="modal-title" style="margin:0;">Edit Language</h4>
        <button type="button" class="close" onclick="removeAlert()" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <div class="alert alert-success modal-alert" id="modal_alert" style="display:none;"></div>  
        <form class="form-horizontal" role="modal">

          <div class="form-group row col-sm-12">
            <label class="control-label col-sm-2"for="id">ID</label>
              <input type="text" class="form-control col-sm-10" id="lanuage_id_in" value="{{$id}}" disabled>
          </div>

          <div class="form-group row col-sm-12">
            <label class="control-label col-sm-2"for="title">Name</label>
            <input type="name" class="form-control col-sm-10" id="language_name_in" value="{{$name}}" disabled>
          </div>

          <div class="form-group row col-sm-12">
            <label class="control-label col-sm-2"for="body">Score</label>
            <textarea type="name" class="form-control col-sm-10 language_score_in" id="language_score_in">{{$score}}</textarea>
          </div>
        </form>
               
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary actionBtn" onclick="updateLanguage({{$id}} ,90 )">
          <span id="footer_action_button" class="glyphicon glyphicon-edit">Update</span>
        </button>

        <button type="button" class="btn btn-warning" onclick="removeAlert()" data-dismiss="modal">
          <span class="glyphicon glyphicon"></span>close
        </button>

      </div>
    </div>
<script>
    var scoree = $('.language_score_in').val();
</script>