@extends('layouts.app')

@section('content')
<style>
.form-control{
display: flex;
margin: 5px;
}
</style>
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-6">
            <div class="card enc">
                <div class="card-header">HILL Encryption Test</div>

                <div class="card-body">
                   <form action="/hill/enc" method="POST">
                       <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                       <div class="form-group">
                           <label class="form-group">Key</label>
                           <select id="choice">
                                <option value="m22">2 X 2 Matrix</option>
                                <option value="m33">3 X 3 Matrix</option>
                           </select>
                           <!-- <form id="m22">
                                <input id="tw0" type="number" name="index" min="0" max="25">
                                <input id="tw1" type="number" name="index" min="0" max="25"> <br />
                                <input id="tw2" type="number" name="index" min="0" max="25">
                                <input id="tw3" type="number" name="index" min="0" max="25">
                            </form> -->

                            <br>
                            <form id="m33">
                                <input id="tr0" type="number" name="index" min="0" max="25">
                                <input id="tr1" type="number" name="index" min="0" max="25">
                                <input id="tr2" type="number" name="index" min="0" max="25"><br>
                                <input id="tr3" type="number" name="index" min="0" max="25">
                                <input id="tr4" type="number" name="index" min="0" max="25">
                                <input id="tr5" type="number" name="index" min="0" max="25"><br>
                                <input id="tr6" type="number" name="index" min="0" max="25">
                                <input id="tr7" type="number" name="index" min="0" max="25">
                                <input id="tr8" type="number" name="index" min="0" max="25">
                            </form>
                       </div>
                       <div class="form-group">
                        <label class="form-group">Plane Text</label>
                        <textarea id="planeText" type="text" name="msg" class="form-control" ></textarea>
                        </div>
                        <div class="form-group"> 
                            <input type="button" name="submit" value="Encrypt" class="btn btn-primary" onclick="enc();" />
                        </div>
                   </form>
                </div>
                <div class="card-footer">
                        <ul class="resultlist">
    
                        </ul>
                    </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card dec">
                <div class="card-header">HILL Decryption Test</div>

                <div class="card-body">
                        <form action="/hill/dec" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <div class="form-group">
                                <label class="form-group">Key</label>
                                <input type="text" name="key" class="form-control" value="this is a 24 byte key !!" />
                            </div>
                            <div class="form-group">
                             <label class="form-group">Cipher Text</label>
                             <textarea id="cipherText" type="text" name="msg" class="form-control" ></textarea>
                             </div>
                             <div class="form-group">
                                 <input type="button" name="submit" value="Decrypt" class="btn btn-success" onclick="dec();" />
                             </div>
                        </form>
                </div>
                <div class="card-footer">
                    <ul class="resultlist">

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

function enc() {
    var key = [[1,2,3],[4,5,6],[7,8,8]];
    var msg = document.getElementById("planeText").value;
    var m0 = document.getElementById("tr0").value;
    var m1 = document.getElementById("tr1").value;
    var m2 = document.getElementById("tr2").value;
    var m3 = document.getElementById("tr3").value;
    var m4 = document.getElementById("tr4").value;
    var m5 = document.getElementById("tr5").value;
    var m6 = document.getElementById("tr6").value;
    var m7 = document.getElementById("tr7").value;
    var m8 = document.getElementById("tr8").value;
    if ( m0 == "" || m1 == "" || m2 == "" || m3 == "" || m4 == ""
    || m5 == "" || m6 == "" || m7 == ""|| m8 == "") {
      alert("Please fill in key matrix");
    }
    else {
      key[0][0] = m0;
      key[0][1] = m1;
      key[0][2] = m2;
      key[1][2] = m3;
      key[1][1] = m4;
      key[1][2] = m5;
      key[2][0] = m6;
      key[2][1] = m7;
      key[2][2] = m8;
    console.log(key);
    }
    // alert(array33);
    $.ajax({
        method:'POST',
        url:'/hill/enc',
        dataType:'html',
        data:{
            '_token': "{{ csrf_token() }}",
            'msg':msg,
            'key':key
        },
        success:function (data) {
            $(".enc .resultlist").append('<li>'+data+'</li>');
        }
    });
}

function dec() {
// alert('test');
    var key = [[1,2,3],[4,5,6],[7,8,8]];
    var msg = document.getElementById("cipherText").value;
    var m0 = document.getElementById("tr0").value;
    var m1 = document.getElementById("tr1").value;
    var m2 = document.getElementById("tr2").value;
    var m3 = document.getElementById("tr3").value;
    var m4 = document.getElementById("tr4").value;
    var m5 = document.getElementById("tr5").value;
    var m6 = document.getElementById("tr6").value;
    var m7 = document.getElementById("tr7").value;
    var m8 = document.getElementById("tr8").value;
    if ( m0 == "" || m1 == "" || m2 == "" || m3 == "" || m4 == ""
    || m5 == "" || m6 == "" || m7 == ""|| m8 == "") {
      alert("Please fill in key matrix");
    }
    else {
      key[0][0] = m0;
      key[0][1] = m1;
      key[0][2] = m2;
      key[1][2] = m3;
      key[1][1] = m4;
      key[1][2] = m5;
      key[2][0] = m6;
      key[2][1] = m7;
      key[2][2] = m8;
    console.log(key);
    }
    
    $.ajax({
        method:'POST',
        url:'/hill/dec',
        dataType:'html',
        data:{
            '_token': "{{ csrf_token() }}",
            'msg':msg,
            'key':key
        },
        success:function (data) {
            $(".dec .resultlist").append('<li>'+data+'</li>');
        }
    });
}
</script>
@endsection
