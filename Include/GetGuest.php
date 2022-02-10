<!--Basic Modal -->
<div class="modal fade text-left modal-borderless" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">ข้อมูลลูกค้า</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-loader" style="display: none; text-align: center;"></div>

                <!-- content will be load here -->
                <div id="dynamic-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger ml-1" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">ปิด</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on('click', '#getUser', function(e) {

            e.preventDefault();

            var uid = $(this).data('id'); // it will get id of clicked row

            $('#dynamic-content').html(''); // leave it blank before ajax call
            $('#modal-loader').show(); // load ajax loader

            $.ajax({
                    url: 'GetProfile.php',
                    type: 'POST',
                    data: 'id=' + uid,
                    dataType: 'html'
                })
                .done(function(data) {
                    console.log(data);
                    $('#dynamic-content').html('');
                    $('#dynamic-content').html(data); // load response 
                    $('#modal-loader').hide(); // hide ajax loader	
                })
                .fail(function() {
                    $('#dynamic-content').html('Something went wrong, Please try again...');
                    $('#modal-loader').hide();
                });

        });

    });
</script>