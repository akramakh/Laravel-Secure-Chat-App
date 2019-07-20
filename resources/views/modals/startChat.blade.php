<div class="modal-content" style="width: auto;">
            <form>
                    {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title"  style="margin:0;">Create Chat</h4>
                    <button type="button" class="close" onclick="removeAlert()" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success modal-alert" id="modal_alert" style="display:none;">100</div>
                    <div class="form-group row">
                        <label for="dim" class="col-md-2">Matrix</label>

                        <select id="dim" name="dim" class="col-md-8" onchange="displayMatrix();">
                                <option value="2" >2 X 2</option>
                                <option value="3">3 X 3</option>
                        </select>
                    </div>
                    <div id="m33" class="form-group row " style="display:none;">
                        <label for="matrix33" class="col-md-2">Key</label>
                        <div id="matrix33" class="col-md-8" style="padding:0;">
                                <input id="tr0" type="number" name="index" value="1" min="0" max="25">
                                <input id="tr1" type="number" name="index" value="2" min="0" max="25">
                                <input id="tr2" type="number" name="index" value="3" min="0" max="25"><br>
                                <input id="tr3" type="number" name="index" value="4" min="0" max="25">
                                <input id="tr4" type="number" name="index" value="5" min="0" max="25">
                                <input id="tr5" type="number" name="index" value="6" min="0" max="25"><br>
                                <input id="tr6" type="number" name="index" value="7" min="0" max="25">
                                <input id="tr7" type="number" name="index" value="8" min="0" max="25">
                                <input id="tr8" type="number" name="index" value="8" min="0" max="25">
                        </div>
                        
                        
                    </div>
                    <div id="iv3" class="form-group row"  style="display:none;">
                        <label for="iv33" class="col-md-2">Initial Vector</label>
                        <input id="iv33" class="col-md-8" type="text" name="iv33" value="xyz" min="3" max="3" required>
                    </div>
                    <div id="m22" class="form-group row " >
                        <label for="last_name" class="col-md-2">Key</label>
                        <div class="col-md-8" style="padding:0;">
                                <input id="tr00" type="number" name="index" value="1" min="0" max="25">
                                <input id="tr01" type="number" name="index" value="2" min="0" max="25"><br>
                                <input id="tr02" type="number" name="index" value="3" min="0" max="25">
                                <input id="tr03" type="number" name="index" value="4" min="0" max="25">

                        </div>
                        
                    </div>
                    <div id="iv2" class="form-group row">
                        <label for="iv22" class="col-md-2">Initial Vector</label>
                        <input id="iv22" class="col-md-8" type="text" name="iv22" value="xy" min="2" max="2" required>
                    </div>

                </div>
                <div class="modal-footer">
                <button id="createbtn" type="button" class="btn btn-success" id="add_skill" onclick="create22(this);">
                    <i class="fa fa-plus"></i> Add
                </button>
                <button type="button" class="btn btn-defualt" onclick="removeAlert()" data-dismiss="modal">
                    <i class="fa fa-remove"></i> Cancle
                </button>
            </div>
            </form>
        </div>
<script>
    "use strict";

    function displayMatrix(){
        var dim = document.getElementById("dim").value;
        var m22 = document.getElementById("m22");
        var m33 = document.getElementById("m33");
        if(dim == 2){
            $('#m33').hide();
            $('#m22').show();
            $('#iv3').hide();
            $('#iv2').show();
            $("#createbtn").attr("onclick","create22(this)");
        }else if(dim == 3){
            $('#m22').hide();
            $('#m33').show();
            $('#iv2').hide();
            $('#iv3').show();
            $("#createbtn").attr("onclick","create33(this)");
        }
    }
    function create22(e) {
            var key = [[1,2],[4,5]];
            var dim = document.getElementById("dim").value;
            var m0 = document.getElementById("tr00").value;
            var m1 = document.getElementById("tr01").value;
            var m2 = document.getElementById("tr02").value;
            var m3 = document.getElementById("tr03").value;

            var iv = document.getElementById("iv22").value;

            if ( m0 == "" || m1 == "" || m2 == "" || m3 == "") {
              alert("Please fill in key matrix");
            }
            else {
              key[0][0] = m0;
              key[0][1] = m1;
              key[1][0] = m2;
              key[1][1] = m3;

            console.log(key);
            }
            // alert(dim);
            $.ajax({
                method:'POST',
                url:'/chat/create',
                dataType:'html',
                data:{
                    '_token': "{{ csrf_token() }}",
                    'creator_id':"{{ Auth::user()->id }}",
                    'member_id': "{{ $user->id }}",
                    'dim':dim,
                    'key':key.toString(),
                    'iv':iv
                },
                success:function (data) {
                    $('.modal-alert').show();
                    $('.modal-alert').text(data);
                    // console.log(data);
                }
            });
        }

    function create33(e) {
            var key = [[1,2,3],[4,5,6],[7,8,8]];
            var dim = document.getElementById("dim").value;
            var m0 = document.getElementById("tr0").value;
            var m1 = document.getElementById("tr1").value;
            var m2 = document.getElementById("tr2").value;
            var m3 = document.getElementById("tr3").value;
            var m4 = document.getElementById("tr4").value;
            var m5 = document.getElementById("tr5").value;
            var m6 = document.getElementById("tr6").value;
            var m7 = document.getElementById("tr7").value;
            var m8 = document.getElementById("tr8").value;

            var iv = document.getElementById("iv33").value;

            if ( m0 == "" || m1 == "" || m2 == "" || m3 == "" || m4 == ""
            || m5 == "" || m6 == "" || m7 == ""|| m8 == "") {
              alert("Please fill in key matrix");
            }
            else {
              key[0][0] = m0;
              key[0][1] = m1;
              key[0][2] = m2;
              key[1][0] = m3;
              key[1][1] = m4;
              key[1][2] = m5;
              key[2][0] = m6;
              key[2][1] = m7;
              key[2][2] = m8;
            console.log(key);
            }
            // alert(dim);
            $.ajax({
                method:'POST',
                url:'/chat/create',
                dataType:'html',
                data:{
                    '_token': "{{ csrf_token() }}",
                    'creator_id':"{{ Auth::user()->id }}",
                    'member_id': "{{ $user->id }}",
                    'dim':dim,
                    'key':key.toString(),
                    'iv':iv
                },
                success:function (data) {
                    $('.modal-alert').show();
                    $('.modal-alert').text(data);
                    // console.log(data);
                }
            });
        }
</script>        