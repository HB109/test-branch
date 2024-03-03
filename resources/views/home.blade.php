@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
    <br>

    @if(session()->get('success'))
        <div class="alert alert-success mt-2">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{route('studentCreate')}}" class="btn btn-info btn-xs mb-2">Add Student</a>
    <table class="table table-striped data-table">
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
  </table>

  <!-- Modal -->
<div class="modal fade" id="userShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Student Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Name:</strong> <span id="user-name"></span></p>
        <p><strong>Phone:</strong> <span id="user-phone"></span></p>
        <p><strong>Email:</strong> <span id="user-email"></span></p>
        <p><strong>Gender:</strong> <span id="user-gender"></span></p>
        <p><strong>Date of Birth:</strong> <span id="user-date-of-birth"></span></p>        
        <p><strong>Photo:</strong> <img src="#" id="imgs"></p>
        <p><strong>Resume:</strong><br><a href="#" id="res"></a></p>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>

     $(function () {
        viewFunction();

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('home') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'first_name', name: 'first_name'},
                {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
      });

    function viewFunction(id){
            $.ajax({
                    type:"GET",
                    url:"student-view"+'/'+id,
                    dataType: "json",
                    data:id,
                    success:function(data){
                        // console.log(data.success);
                        let img = "{{ url('storage') }}"+'/'+data.success.photo;
                        let resume = "{{ url('storage') }}"+'/'+data.success.resume;
                        let downloadUrl = "{{ url('download') }}"+'/'+data.success.id;
                        $('#userShowModal').modal('show');
                        $('#user-name').html(data.success.first_name+' '+data.success.last_name);
                        $('#user-phone').html(data.success.phone);
                        $('#user-email').html(data.success.email);
                        let gender = '';
                        if(data.success.gender== 1){
                            gender = 'Male';
                        }else{
                            gender = "Female";
                        }
                        $('#user-gender').html(gender);
                        $('#user-date-of-birth').html(data.success.date_of_birth);
                        $('#resume').attr("src",resume);
                        if (!$.trim(data.success.photo)){                             
                        }else{
                            $("#imgs").attr("src", img).css({ 'height': '100px', 'width': '100px' });
                        }
                        
                        if (!$.trim(data.success.resume)){
                        }else{
                            $("#res").attr("href", downloadUrl).text('resume download'); 
                        }
                             
                    },

                    error:function(e){
                        console.log(e.responsetext);                   
                    }
        })
    }
    
</script>
@endsection
