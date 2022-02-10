
        <script type="text/javascript" language="javascript" >
        
          $(document).ready(function(){
          $('.input-daterange').datepicker({
          todayBtn:'linked',
          format: "yyyy-mm-dd",
          });
              
         load_data();
         function load_data(is_category, SelectDate='', category=''){
          var dataTable = $('#product_data').DataTable({

           "processing":true,
           "serverSide":true,
           "order":[],
           "ajax":{
            url:"FetchDataGuest.php",
            type:"POST",
            data:{is_category:is_category, SelectDate:SelectDate,category:category}
           },
           "columnDefs":[
            {
             "targets":[2],
             "orderable":false,
            },
           ],
            "language": {
           "sEmptyTable":     "ไม่มีข้อมูลในตาราง",
            "sInfo":           "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            "sInfoEmpty":      "แสดง 0 ถึง 0 จาก 0 รายการ",
            "sInfoFiltered":   "",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "แสดง _MENU_ รายการ",
            "sLoadingRecords": "กำลังโหลดข้อมูล...",
            "sProcessing":     "กำลังดำเนินการ...",
            "sSearch":         "ค้นหา : ",
            "sZeroRecords":    "ไม่พบข้อมูล",
            "oPaginate": {
                "sFirst":    "หน้าแรก",
            "sPrevious": "ก่อนหน้า",
                "sNext":     "ถัดไป",
            "sLast":     "หน้าสุดท้าย"
            },
            "oAria": {
                "sSortAscending":  ": เปิดใช้งานการเรียงข้อมูลจากน้อยไปมาก",
            "sSortDescending": ": เปิดใช้งานการเรียงข้อมูลจากมากไปน้อย"
            }
          }
          });
         }

         $(document).on('click', '#search', function(){
          var category = $('#category').val();
          var SelectDate = $('#SelectDate').val();
          $('#product_data').DataTable().destroy();
          if(category != '' && SelectDate != ''){
           load_data(category,SelectDate);
          
          }else
          {
           load_data(bootbox.alert('<p style="text-align: center;"><br><br>กรูณาเลือกวันที่ และร้านอาหาร ที่ต้องการแสดงข้อมูลก่อนกดค้นหา</br></br></p>'));
          }
         });
        });

        </script>

        <div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
             <div class="modal-dialog"> 
                  <div class="modal-content"> 
                  
                       <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                            <h4 class="modal-title" style="font-family: 'Trirong', serif;">
                            	<i class="ti-info-alt"></i> ข้อมูลลูกค้า
                            </h4> 
                       </div> 
                       <div class="modal-body"> 
                       
                       	   <div id="modal-loader" style="display: none; text-align: center;">
                       	   </div>
                            
                           <!-- content will be load here -->                          
                           <div id="dynamic-content"></div>
                             
                        </div> 
                        <div class="modal-footer"> 
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>  
                        </div> 
                        
                 </div> 
              </div>
       </div><!-- /.modal --> 

    <script type="text/javascript" language="javascript" >
    $(document).ready(function(){
	$(document).on('click', '#getPro', function(e){
		
		e.preventDefault();
		var uid = $(this).data('id');   // it will get id of clicked row
		$('#dynamic-content').html(''); // leave it blank before ajax call
		$('#modal-loader').show();      // load ajax loader
		$.ajax({
			url: 'GetGuestProfile.php',
			type: 'POST',
			data: 'id='+uid,
			dataType: 'html'
		})
		.done(function(data){
			console.log(data);	
			$('#dynamic-content').html('');    
			$('#dynamic-content').html(data); // load response 
			$('#modal-loader').hide();		  // hide ajax loader	
		})
		.fail(function(){
			$('#dynamic-content').html('Something went wrong, Please try again...');
			$('#modal-loader').hide();
		});
	});
});

</script>

    <script src="js/bootbox.min.js"></script>		
<script>
	$(document).ready(function(){
            $(document).on('click', '#UpST', function(e){
			e.preventDefault();
                
            var uid = $(this).attr('data-id');
			var parent = $(this).parent("td").parent("tr");
			
			bootbox.dialog({
			  message: "<p style='font-size:18px;'; >ยืนยันการอัพเดทสถานะโต๊ะลูกค้า ?</br></br>",
			  title: "ยืนยัน !",
			  buttons: {
				
				danger: {
				  label: "ยืนยัน",
				  className: "btn-success",
				  callback: function() {
					  
					  $.ajax({
						  type: 'POST',
						  url: 'UpdateStatus.php',
						  data: 'id='+uid,
			              dataType: 'html'
					  })
					  .done(function(response){
						  window.location.reload();
						  parent.fadeOut('slow');
					  })
					  .fail(function(){
						  bootbox.alert('Something Went Wrog ....');
					  })
				  }
				},success: {
				  label: "ยกเลิก",
				  className: "btn-danger",
				  callback: function() {
					 $('.bootbox').modal('hide');
				  }
			  }
				}
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
        $(document).on('click', '#DeleteGuest', function(e){
			e.preventDefault();
			var pid = $(this).attr('data-id');
			var parent = $(this).parent("td").parent("tr");
			
			bootbox.dialog({
			  message: "<p style='font-size:18px;'; >ยืนยันลบข้อมูลการจองโต๊ะลูกค้า ?</br></br>",
			  title: "<i class='glyphicon glyphicon-trash'></i> Delete !",
			  buttons: {
				
				danger: {
				  label: "Delete!",
				  className: "btn-danger",
				  callback: function() {
					  
					  $.ajax({
						  type: 'POST',
						  url: 'DeleteGuest.php',
						  data: 'DeleteGuest='+pid
					  })
					  .done(function(response){
						  bootbox.alert(response);
						  parent.fadeOut('slow');
					  })
					  .fail(function(){
						  bootbox.alert('Something Went Wrog ....');
					  })
				  }
				},success: {
				  label: "Cancel",
				  className: "btn-success",
				  callback: function() {
					 $('.bootbox').modal('hide');
				  }
			  }
				}
			});
		});
	});
</script>