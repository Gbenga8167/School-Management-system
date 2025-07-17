@extends('backend.admin_profile.admin.admin_dashboard');
@section('admin')

    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">UPDATE TEACHER INFO</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Update</a></li>
                    <li class="breadcrumb-item active"> Teacher</li>
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

                <h4 class="card-title">Update - Teacher </h4>
              
                <form action="{{route('update.teacher')}}" method="post"  enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{$teachers->id}}">

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Fullname</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="full_name"  type="text" value="{{$teachers->name}}" >
                       
                    </div>
                   
                </div>

                <div class="mb-3">
                     <label>Address</label>
                     <div>
                         <textarea required="" name="address" class="form-control" rows="5" style="height: 173px;">{{$teachers->address}}</textarea>
                     </div>
                 </div>


                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Nationality</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="nationality"  type="text"  value="{{$teachers->nationality}}" >
                    
                    </div>
                   
                </div>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Phone Number</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="phone_number"  type="text"  value="{{$teachers->phone_number}}" >
                     
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Qualification</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="qualification"  type="text"  value="{{$teachers->qualification}}" >
                     
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Work Experience</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="experience"  type="text"  value="{{$teachers->work_experience}}" >
                     
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Marital Status</label>
                    <div class="col-sm-10">

                    <input class="form-check-input"  required type="radio" name="marital" checked="" value="Married" {{$teachers->marital_status == 'Married'? 'checked' : ''}}>
                    <label class="form-check-label" for="formRadios1">Married </label>


                    <input class="form-check-input"required type="radio" name="marital" value="Single" {{$teachers->marital_status == 'Single'? 'checked' : ''}}>
                    <label class="form-check-label" for="formRadios1">Single </label>

                    <input class="form-check-input"required type="radio" name="marital" value="Divorce" {{$teachers->marital_status == 'Divorce'? 'checked' : ''}}>
                    <label class="form-check-label" for="formRadios1">Divorce </label>
                 
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">State Of Origin</label>
                    <div class="col-sm-10">
                    <select  name="State_of_origin" required class="form-select" aria-label="Default select example">
                    <option selected="" value="{{$teachers->state_of_origin}}">{{$teachers->state_of_origin}}</option>
                    <option value="Ekiti">Ekiti</option>  
                    <option value="Osun">Osun</option>
                    <option value="Oyo">Oyo</option>
                    <option value="Lagos">Lagos</option>
                    <option value="Delta">Delta</option>
                    <option value="Ondo">Ondo</option>                             
                     </select>
                    </div>
                   
                </div>

    

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">DOB</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="dob"  type="date"  value="{{$teachers->dob}}">
                        
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
                    <img id="ShowImage" src="{{ empty($teachers->photo)? asset('uploads/no_image.png') : asset('uploads/teachers_photos/'.$teachers->photo)}}" alt="avatar-4" class="rounded avatar-md">
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Gender</label>
                    <div class="col-sm-10">

                    <input class="form-check-input"  required type="radio" name="gender"  value="Male" {{$teachers->gender == 'Male'? 'checked' : ''}} >
                    <label class="form-check-label" for="formRadios1"> Male </label>


                    <input class="form-check-input"required type="radio" name="gender" value="Female" {{$teachers->gender == 'Female'? 'checked' : ''}}>
                    <label class="form-check-label" for="formRadios1"> Female </label>
                 
                    </div>
                   
                </div>


                

                <!-- end row -->

                <button type="submit" class="btn btn-primary waves-effect waves-light">Update Teacher</button>
                
                </form>
               
                

              
            </div>
        </div>
    </div> 
</div>
  

<script>
    //image update
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



