<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete_form" method="POST" action={{ action('AccessController@destroy', [$username, request('username')]) }}>
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data ini?</p>
                        <p>
                            Akses : <span id="username_delete"></span><br>
                            URL : <span id="url_delete"></span><br>
                            Departemen : <span id="departemen_delete"></span><br>
                        </p>
                        <input type="hidden" id="delete_token"/>
                        <input type="hidden" id="id"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger" id="delete-access-btn">Hapus</button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function () {
        $(document).on('click', '#delete-access', function (e) {
            e.preventDefault();
            $('#DeleteModal').modal('show');
        // populate modal
            var id = $(this).data('id');
            var url = $(this).attr('href');
            $.get(url, function (data) {
                    // success data
                    // console.log(id);
                    $('#id').val(id);
                    $('#username_delete').html(data.name);
                    $('#url_delete').html(data.url);
                    $('#departemen_delete').html(data.departemen);
                    $('#DeleteModal').modal('show');
                    
                }) 
            })
        //end populate modal
        
        // sent http request
        $(document).on('click', '#delete-access-btn', function (e) {
    
            $('#DeleteModal').on('hidden.bs.modal', function () {
                $(".text-danger").remove();
                $(this).find('form').trigger('reset');
            })
        
        var form = $('#delete_form');
            form.submit(function(e) {
                e.preventDefault();
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                $.ajax({
                    url     : form.attr('action') + "/" +  $('#id').val(),
                    type    : 'POST',
                    data    : form.serialize(),
                    id      : $('#id').val(),
                    // timeout : 200,
                    success : function ( json )
                    {
                        console.log(json);
                        $('#DeleteModal').modal('hide');
                        $(document.body).removeClass("modal-open");
                        $(".modal-backdrop").remove();
                        $('.access').html(json);
                        $('#deletemsg').removeClass('d-none');
                        setTimeout(function(){
                            $('#deletemsg').addClass('d-none'); }, 5000
                        );
                    },
                    error: function (json)
                    {
                        alert('gagal menghapus.');
                    },
                });
            });
        });
    });
    </script>