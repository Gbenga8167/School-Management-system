@extends('backend.admin_profile.admin.admin_dashboard');
@section('admin')

    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ADD STUDENT INFO</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Add</a></li>
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

                <h4 class="card-title">Add - Student </h4>
              
                <form action="{{route('store.student')}}" method="post"  enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" value='3' name='role'>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Fullname</label>
                    <div class="col-sm-10">
                        <input class="form-control"  required name="full_name"  type="text" placeholder="Full Name" >
                       
                    </div>
                    <!-- end row -->
                     
                   
                </div>

                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input class="form-control" required name="email"  type="text" placeholder="Email" >
                    
                    </div>
                   
                </div>
                <!-- end row -->




                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Roll Id</label>
                    <div class="col-sm-10">
                        <input class="form-control" required name="roll_id"  type="text" placeholder="Roll_Id"  value="AGM/2025/">
                     
                    </div>
                   
                </div>
                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">DOB</label>
                    <div class="col-sm-10">
                        <input class="form-control" required name="dob"  type="date" >
                        
                    </div>
                   
                </div>
                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Gender</label>
                    <div class="col-sm-10">

                    <input class="form-check-input"  required type="radio" name="gender" checked="" value="Male">
                    <label class="form-check-label" for="formRadios1"> Male </label>


                    <input class="form-check-input" required type="radio" name="gender" value="Female">
                    <label class="form-check-label" for="formRadios1"> Female </label>
                 
                    </div>
                   
                </div>
                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">User name</label>
                    <div class="col-sm-10">
                        <input class="form-control" required  name="username"  type="text" placeholder="User Name" >
                       
                    </div>
                   
                </div>

                <!-- end row -->


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input class="form-control" required name="password" required type="password" placeholder="Password">
                        
                    </div>
                   
                </div>
                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Photo</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="photo" id="image" required type="file">
                        
                    </div>
                   
                </div>
                <!-- end row -->


                <div class="row mb-3">
                    <label for="example-email-input" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                    <img id="ShowImage" src="{{asset('uploads/no_image.png')}}" alt="avatar-4" class="rounded avatar-md">
                    </div>
                </div>
                <!-- end row -->


                <h4 class="card-title" style="text-align:center">Parent Details</h4>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Parent name</label>
                    <div class="col-sm-10">
                        <input class="form-control" required  name="parent_name"  type="text" placeholder="Parent Name" >
                       
                    </div>
                    <!-- end row -->

                   
                </div>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Parent Occupation</label>
                    <div class="col-sm-10">
                        <input class="form-control"  required   name="parent_occupation"  type="text" placeholder="Parent Occupation" >
                       
                    </div>
                   
                </div>
                <!-- end row -->




                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Gender</label>
                    <div class="col-sm-10">

                    <input class="form-check-input"  required type="radio" name="parent_gender" checked="" value="Male">
                    <label class="form-check-label" for="formRadios1"> Male </label>


                    <input class="form-check-input"required type="radio" name="parent_gender" value="Female">
                    <label class="form-check-label" for="formRadios1"> Female </label>
                 
                    </div>
                   
                </div>
                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">State Of Origin</label>
                    <div class="col-sm-10">
                    <select  name="State_of_origin" required class="form-select" aria-label="Default select example">
                    <option selected="">-- Select State --</option>
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
                        <input class="form-control" required name="phone_number"  type="text" placeholder="Phone Number" >
                    
                    </div>
                   
                </div>
                <!-- end row -->


                <div class="mb-3">
                     <label>Contact Address</label>
                     <div>
                         <textarea required="" name="address" required class="form-control" rows="5" style="height: 173px;"  placeholder="Address" ></textarea>
                     </div>
                 </div>
                 <!-- end row -->

                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Nationality</label>
                    <div class="col-sm-10">
                        <input class="form-control" required name="nationality"  type="text" placeholder="Nationality" >
                    
                    </div>
                   
                </div>



                <!-- end row -->

                <button type="submit" class="btn btn-primary waves-effect waves-light">Add Student</button>
                
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



