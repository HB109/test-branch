@extends('layouts.app')

@section('content')
<div class="container">
    <!-- <div class="row justify-content-center">
        <div class="col-md-8">
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
    </div>  -->

   
   <a href="{{route('home')}}" class="btn btn-info btn-xs mb-2">Back</a>
   <div class="mt-2" id="show_msg"></div>
    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul></ul>
    </div>
  <h2>Add Student</h2>
  <form action="" id="myForm" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-1">
      <label for="email"><b>First Name:</b></label>
      <input type="text" class="form-control" id="first_name" placeholder="Enter first_name" name="first_name">
      <span id="first_name_error" class="text-danger"></span>
    </div>
    <div class="form-group mb-1">
      <label for="email"><b>Last Name:</b></label>
      <input type="text" class="form-control" id="last_name" placeholder="Enter last_name" name="last_name">
      <span id="last_name_error" class="text-danger"></span>
    </div>

    <div class="form-group mb-1">
      <label for="email"><b>Phone:</b></label>
      <input type="text" class="form-control" id="phone" placeholder="Enter phone" name="phone">
      <!-- <span id="first_name_error"></span> -->
    </div>

    <div class="form-group mb-1">
      <label for="email"><b>Email:</b></label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>

    <div class="form-group mb-1">
      <label for="email"><b>Date of Birth:</b></label>
      <input type="date" class="form-control" id="dob" placeholder="Enter dob" name="date_of_birth">
    </div>

    <div class="form-group mb-1">
      <label for="email"><b>Gender:</b></label>
      Male&nbsp;<input type="radio" class="form-control1" id="email" value="1" placeholder="Enter email" name="gender" checked>
      Female&nbsp;<input type="radio" class="form-control1" id="email" value="2" placeholder="Enter email" name="gender">
    </div>

    <div class="form-group mb-1">
      <label for="email"><b>Resume:</b></label>
      <input type="file" class="form-control" id="email" placeholder="Enter email" name="resume">
    </div>

    <div class="form-group mb-1">
      <label for="email"><b>Applicant’s photo:</b></label>
      <input type="file" class="form-control" id="email" placeholder="Enter email" name="photo">
    </div>
    
    
    <button type="submit" class="btn btn-primary mt-1" id="btn_submit">Submit</button>
  </form>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
     $(document).ready(function(){
        $('#myForm').submit(function(e){
            e.preventDefault();
            
            var form =  $('#myForm')[0];
            var data = new FormData(form);

            $('#btn_submit').prop('disabled', true);

            $.ajax({
                type:"POST",
                url:"{{route('studentFormSave')}}",
                crossDomain: true,
                dataType: "json",
                data:data,
                contentType: false,
                processData: false,
                success:function(data){
                    console.log(data);
                    if($.isEmptyObject(data.error)){
                       $('#show_msg').addClass('alert alert-success').html(data.success);
                       $('#btn_submit').prop('disabled', false);
                       $('.print-error-msg').hide();
                    }else{
                        printErrorMsg(data.error);
                        $('#btn_submit').prop('disabled', false);
                    }
                },
                error:function(e){
                    console.log(e.responsetext);
                    $('#btn_submit').prop('disabled', false);
                }
            });
        });

        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }


      });
</script>
@endsection
