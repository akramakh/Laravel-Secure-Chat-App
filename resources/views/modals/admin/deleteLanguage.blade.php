<style>
    .th{
        background-color:#e97979;
        color:#FFF;
        width:135px;
    }
    
</style>
    <div class="modal-content" style="width:auto;">
      <div class="modal-header">
          <h4 class="modal-title" style="margin:0;">Delete Language</h4>
        <button type="button" class="close" onclick="removeAlert()" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <div class="alert alert-success modal-alert" id="modal_alert" style="display:none;"></div>  
                {{-- Form Delete Post --}}
          <table class="table">
          <tr>
            <td class="th"for="id">ID</td>
            <td class=""for="id">{{$language->id}}</td>
          </tr>
          
          <tr>
            <td class="th"for="id">NAME</td>
            <td class=""for="id">{{$language->name}}</td>
          </tr>

          <tr>
            <td class="th"for="id"># Users Related to</td>
            <td class=""for="id">{{$count}}</td>
          </tr>
          
        </table>
          
          <div class="deleteContent" style="color:red;font-size:1.2em">
          Are You sure want to delete <span class="title">?</span>
          <span class="hidden id"></span>
        </div>
          
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-danger actionBtn" onclick="deleteLanguage({{$language->id}})">
          <span id="footer_action_button" class="glyphicon glyphicon-trash">Delete</span>
        </button>

        <button type="button" class="btn btn-warning" onclick="removeAlert()" data-dismiss="modal">
          <span class="glyphicon glyphicon"></span>close
        </button>

      </div>
    </div>