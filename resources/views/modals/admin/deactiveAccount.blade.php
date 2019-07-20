<div class="modal-content" style="width: auto;">
                <form>
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h4 class="modal-title"  style="margin:0;">Warning</h4>
                        <button type="button" class="close" onclick="removeAlert()" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal" action="#" method="post">
                            <!-- Start Date of Birth Field -->
                            <div class="row" style="margin:0;">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-8">
                                    <div class="setting-header ">
                                        <div class="img-cont"><img src="#"/></div>
                                        <div class="name-cont">
                                            <a href="#" target="_blank" style=" " ></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2"></div>
                            </div>
                            <div class="row" style="margin:0 0 10px 0;">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-10">
                                    <b class="col-lg-12 modal-para" style="text-align:center;">This Option will put your Account in unactive state AND You will NOT use any feature in this Application </b>
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                            <div class="row" style="margin:0 0 10px 0;">
                                <div class="col-lg-1"></div>
                                <div class="form-group form-group-lg col-lg-11">
                                    <label class="col-lg-5 control-label">Time for Deactivate</label>
                                    <div class="col-sm-10 col-lg-6">

                                    </div>
                                </div>

                            </div>
                            <div class="row" style="margin:10px 0;">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-11">
                                    <div class="form-group form-group-lg col-lg-12">
                                        <label class="col-lg-3 control-label">Password</label>
                                        <div class="col-sm-10 col-lg-9">
                                            <input type="hidden" id="oldpassword" value=""/>
                                            <input type="password" id="password" name="password" class="form-control password" style="margin:0;" placeholder="Be carefull"/>
                                            <div id="pass-alert" style="display:none; color:#F00;">Enter password</div>
                                            <div id="err-pass-alert" style="display:none; color:#F00;">ERROR password</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-continue-modal" onclick="addSkillModal(this);">
                            <i class="fa fa-plus"></i> Add
                        </button>
                        <button type="button" class="btn btn-defualt" onclick="removeAlert()" data-dismiss="modal">
                            <i class="fa fa-remove"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>