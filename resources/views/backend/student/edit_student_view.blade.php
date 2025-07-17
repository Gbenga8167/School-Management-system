
@extends('backend.admin_profile.admin.admin_dashboard');
@section('admin')

    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">UPDATE STUDENT INFO</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Update</a></li>
                    <li class="breadcrumb-item active"> Student</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Update - Student </h4>
              
                <form action="{{route('update.student')}}" method="post"  enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{$students->id}}">

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Fullname</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="full_name"  type="text" value="{{$students->name}}" >
                       
                    </div>
                   
                </div>

            

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Roll Id</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="roll_id"  type="text"  value="{{$students->roll_id}}" >
                     
                    </div>
                   
                </div>

            

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">DOB</label>
                    <div class="col-sm-10">
                        <input class="form-control" required name="dob"  type="date"  value="{{$students->dob}}">
                        
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Photo</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="photo" id="image"  type="file">
                        
                    </div>
                   
                </div>



                <div class="row mb-3">
                    <label for="example-email-input" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                    <img id="ShowImage" src="{{ empty($students->photo)? asset('uploads/no_image.png') : asset('uploads/student_photos/'.$students->photo)}}" alt="avatar-4" class="rounded avatar-md">
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Gender</label>
                    <div class="col-sm-10">

                    <input class="form-check-input"  required type="radio" name="gender"  value="Male" {{$students->gender == 'Male'? 'checked' : ''}} >
                    <label class="form-check-label" for="formRadios1"> Male </label>


                    <input class="form-check-input"required type="radio" name="gender" value="Female" {{$students->gender == 'Female'? 'checked' : ''}}>
                    <label class="form-check-label" for="formRadios1"> Female </label>
                 
                    </div>
                   
                </div>

            

                <!-- end row -->

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                    <select  name="status" class="form-select" aria-label="Default select example">
                                                    <option value="1">Active</option>
                                                    <option value="0">In-Active</option>
                                                    </select>
                    </div>
                   
                </div>

                <!-- end row -->


                <h4 class="card-title" style="text-align:center">Parent Details</h4>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Parent name</label>
                    <div class="col-sm-10">
                        <input class="form-control" required  name="parent_name"  type="text" value="{{$students->parent_name}}" >
                       
                    </div>
                    <!-- end row -->

                   
                </div>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Parent Occupation</label>
                    <div class="col-sm-10">
                        <input class="form-control"  required   name="parent_occupation"  type="text" value="{{$students->parent_occupation}}">
                       
                    </div>
                   
                </div>
                <!-- end row -->




                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Gender</label>
                    <div class="col-sm-10">

                    <input class="form-check-input"  required type="radio" name="parent_gender"  value="Male" {{$students->parent_gender == 'Male'? 'checked' : ''}} >
                    <label class="form-check-label" for="formRadios1"> Male </label>


                    <input class="form-check-input"required type="radio" name="parent_gender" value="Female" {{$students->parent_gender == 'Female'? 'checked' : ''}}>
                    <label class="form-check-label" for="formRadios1"> Female </label>
                 
                    </div>
                   
                </div>
                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">State Of Origin</label>
                    <div class="col-sm-10">
                    <select  name="State_of_origin" required class="form-select" aria-label="Default select example">
                    <option selected="" value="{{$students->state_of_origin}}">{{$students->state_of_origin}}</option>
                    <option value="Ekiti">Ekiti</option>  
                    <option value="Osun">Osun</option>
                    <option value="Oyo">Oyo</option>
                    <option value="Lagos">Lagos</option>
                    <option value="Delta">Delta</option>
                    <option value="Ondo">Ondo</option>                             
                     </select>
                    </div>
                   
                </div>
                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Phone Number</label>
                    <div class="col-sm-10">
                        <input class="form-control" required name="phone_number"  type="text" value="{{$students->phone_number}}">
                    
                    </div>
                   
                </div>
                <!-- end row -->


                <div class="mb-3">
                     <label>Contact Address</label>
                     <div>
                         <textarea required="" name="address" required class="form-control" rows="5" style="height: 173px;"  placeholder="Address" >{{$students->address}}</textarea>
                     </div>
                 </div>
                 <!-- end row -->

                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Nationality</label>
                    <div class="col-sm-10">
                        <input class="form-control" required name="nationality"  type="text" value="{{$students->nationality}}">
                    
                    </div>
                   
                </div>



                <button type="submit" class="btn btn-primary waves-effect waves-light">Update Student</button>
                
                </form>
               
                

              
            </div>
        </div>
    </div> 
</div>
  

<script>
  $(document).ready(function(){
 $('#image').on("change", function(e){
    var reader = new FileReader();
    reader.onload = function(e){
        $('#ShowImage').attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files['0']);
 });

    });
</script>
  

@endsection



