<div class="modal-content" style="width: auto;">
            <form>
                    {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title"  style="margin:0;">Add Language</h4>
                    <button type="button" class="close" onclick="removeAlert()" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success modal-alert" id="modal_alert" style="display:none;"></div>
                    <div class="form-group row col-md-12">
                        <label for="language_name" class="col-md-4">Language</label>

                        <select id="Language_name" name="Language_name" class="col-md-8">
                            @foreach($languages as $lang)
                            <option value="{{$lang->id}}">{{$lang->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row col-md-12">
                        <label for="last_name" class="col-md-4">Score</label>
                        <input type="number" class="col-md-8" id="score" name="score">
                    </div>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add_language" onclick="addLanguage(this);">
                    <i class="fa fa-plus"></i> Add
                </button>
                <button type="button" class="btn btn-defualt" onclick="removeAlert()" data-dismiss="modal">
                    <i class="fa fa-remove"></i> Cancle
                </button>
            </div>
            </form>
        </div>