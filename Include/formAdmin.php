
                                        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" onSubmit="JavaScript:return fncSubmit();" name="form">
                                    
                                        <div class="col-md-2">
                                            <input type="text" class="form-control border-input" name="Username" placeholder="Username">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="password" class="form-control border-input" name="password" maxlength="15" placeholder="รหัสผ่าน" onblur="check_num(this)">
                                    
                                            
                                        <script type="text/javascript">
                                        if(!alertify.errorAlert){
                                            alertify.dialog('errorAlert',function factory(){
                                                return{
                                                    build:function(){
                                                    var errorHeader = '<span class="fa fa-times-circle fa-2x" '
                                                    +    'style="vertical-align:middle;color:#e10000;">'
                                                    + '</span>&nbsp;&nbsp; เพิ่มข้อมูลไม่สำเร็จ';
                                                    this.setHeader(errorHeader);
                                                                    }
                                                    };
                                                },true,'alert');
                                        }
                                                function check_num(elm){
	                                               if(elm.value.length<8 || elm.value.length>15){
                                                       alertify.defaults.theme.ok = "btn btn-primary";
		                                               alertify.errorAlert("กรุณากรอก Password จำนวนตัวอักษร 8 - 15 ตัวอักษรเท่านั้น! <br/><br/>");
	                                                   }}
                                            </script>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control border-input" name="Firstname" placeholder="ชื่อ">               
                                        </div>
                                         <div class="col-md-2">   
                                             <input type="text" class="form-control border-input" name="Lastname" placeholder="นามสกุล">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control border-input" name="Tel" maxlength="10" placeholder="เบอร์โทร"
                                                   OnKeyPress="return chkNumber(this)">
                                        </div> 
                                        <div class="col-md-2">
                                        <div class="dropdown">
                                    <select name="Userlevel" id="Userlevel" class=" toggle-divs form-control border-input" >
                                    <option value="" disabled selected>กรุณาเลือก</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Editor">Editor</option></select>
                                            </div>
                                        </div></div>
                                        <div class="text-center">
                                     <button type="submit" name="submit" style="margin-bottom: 15px;" class="btn btn-info btn-fill btn-wd">บันทึก</button>
                                    </div>
                                    <div class="clearfix"></div>
                                      
                                    <script language="JavaScript">
	                                       function chkNumber(ele)
	                                           {
	                                           var vchar = String.fromCharCode(event.keyCode);
	                                           if (vchar<'0' || vchar>'9') return false;
	                                           ele.OnKeyPress=vchar;
	                                           }
                                        </script>
                                   <script type="text/javascript">
                                        if(!alertify.errorAlert){
                                            alertify.dialog('errorAlert',function factory(){
                                                return{
                                                    build:function(){
                                                    var errorHeader = '<span class="fa fa-times-circle fa-2x" '
                                                    +    'style="vertical-align:middle;color:#e10000;">'
                                                    + '</span>&nbsp;&nbsp; เพิ่มข้อมูลไม่สำเร็จ';
                                                    this.setHeader(errorHeader);
                                                                    }
                                                    };
                                                },true,'alert');
                                        }

                                            function fncSubmit()
                                            {
	                                           if(document.form.Username.value == "")
	                                           {
                                                  alertify.defaults.theme.ok = "btn btn-primary";
		                                          alertify.errorAlert("กรุณากรอก Username! <br/><br/>");
		                                          document.form.Username.focus();
		                                          return false;
	                                           }	
                                                
	                                           if(document.form.password.value == "")
	                                           {
		                                          document.form.password.focus();		
		                                          return false;
	                                           }
                                                
                                                if(document.form.Firstname.value == "")
	                                           {
                                                  alertify.defaults.theme.ok = "btn btn-primary";
		                                          alertify.errorAlert("กรุณากรอกชื่อจริง! <br/><br/>");
		                                          document.form.Firstname.focus();		
		                                          return false;
	                                           }
                                                if(document.form.Lastname.value == "")
	                                           {
		                                           alertify.defaults.theme.ok = "btn btn-primary";
                                                   alertify.errorAlert("กรุณากรอกนามสกุล! <br/><br/>");
		                                          document.form.Lastname.focus();		
		                                          return false;
	                                           }
                                                if(document.form.Tel.value == "")
	                                           {
		                                         alertify.defaults.theme.ok = "btn btn-primary";
                                                   alertify.errorAlert("กรุณากรอกเบอร์โทรติดต่อ! <br/><br/>");
		                                          document.form.Tel.focus();		
		                                          return false;
	                                           }
                                                 if(document.form.Userlevel.value == "")
	                                           {
		                                         alertify.defaults.theme.ok = "btn btn-primary";
                                                   alertify.errorAlert("กรุณาเลือกสถานะผู้ดูแลระบบ! <br/><br/>");
		                                          document.form.Tel.focus();		
		                                          return false;
	                                           }
	                                               document.form.submit();
                                                }
                                        </script>   
</form>
                                