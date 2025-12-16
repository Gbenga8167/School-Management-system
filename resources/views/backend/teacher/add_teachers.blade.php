@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')

    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ADD Teacher INFO</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Add</a></li>
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

            @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



                <h4 class="card-title">Add - New Teacher </h4>
              
                <form action="{{route('store.teacher')}}" method="post"  enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" value='2' name='role'>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Fullname</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="full_name" value="{{ old('full_name') }}" type="text" placeholder="Full Name" >
                       
                         @error('full_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   

                   
                </div>


                
                <div class="mb-3">
                     <label>Address</label>
                     <div>
                         <textarea name="address" class="form-control" rows="5" style="height: 173px;"  placeholder="Address">{{ old('address') }}</textarea>
                        
                         @error('address')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                     </div>
                    
                 </div>



                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Nationality</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="nationality"  type="text" placeholder="Nationality" value="{{ old('nationality') }}">
                    
                         @error('nationality')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Phone Number</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="phone_number"  type="text" placeholder="Phone Number" value="{{ old('phone_number') }}">
                    
                         @error('phone_number')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>

                

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Qualification</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="qualification"  type="text" placeholder="Qualification" value="{{ old('qualification') }}" >
                     
                         @error('qualification')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Work Experience</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="experience"  type="text" placeholder="Work Experience" value="{{ old('experience') }}">
                     
                         @error('experience')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div> 
                
                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Marital Status</label>
                    <div class="col-sm-10">

                    <input class="form-check-input" type="radio" name="marital" checked="" value="Married">
                    <label class="form-check-label" for="formRadios1">Married </label>


                    <input class="form-check-input" type="radio" name="marital" value="Single">
                    <label class="form-check-label" for="formRadios1">Single </label>

                    <input class="form-check-input" type="radio" name="marital" value="Divorce">
                    <label class="form-check-label" for="formRadios1">Divorce </label>
                 
                     @error('marital')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>
                

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">State Of Origin</label>
                    <div class="col-sm-10">
                    <select  name="State_of_origin" class="form-select" aria-label="Default select example">
                               <option value="">-- Select State of Origin --</option>
                               <option value="Abia">Abia</option>
                               <option value="Adamawa">Adamawa</option>
                               <option value="Akwa Ibom">Akwa Ibom</option>
                               <option value="Anambra">Anambra</option>
                               <option value="Bauchi">Bauchi</option>
                               <option value="Bayelsa">Bayelsa</option>
                               <option value="Benue">Benue</option>
                               <option value="Borno">Borno</option>
                               <option value="Cross River">Cross River</option>
                               <option value="Delta">Delta</option>
                               <option value="Ebonyi">Ebonyi</option>
                               <option value="Edo">Edo</option>
                               <option value="Ekiti">Ekiti</option>
                               <option value="Enugu">Enugu</option>
                               <option value="Gombe">Gombe</option>
                               <option value="Imo">Imo</option>
                               <option value="Jigawa">Jigawa</option>
                               <option value="Kaduna">Kaduna</option>
                               <option value="Kano">Kano</option>
                               <option value="Katsina">Katsina</option>
                               <option value="Kebbi">Kebbi</option>
                               <option value="Kogi">Kogi</option>
                               <option value="Kwara">Kwara</option>
                               <option value="Lagos">Lagos</option>
                               <option value="Nasarawa">Nasarawa</option>
                               <option value="Niger">Niger</option>
                               <option value="Ogun">Ogun</option>
                               <option value="Ondo">Ondo</option>
                               <option value="Osun">Osun</option>
                               <option value="Oyo">Oyo</option>
                               <option value="Plateau">Plateau</option>
                               <option value="Rivers">Rivers</option>
                               <option value="Sokoto">Sokoto</option>
                               <option value="Taraba">Taraba</option>
                               <option value="Yobe">Yobe</option>
                               <option value="Zamfara">Zamfara</option>
                               <option value="FCT">Federal Capital Territory (Abuja)</option>                          
                     </select>

                      @error('State_of_origin')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">DOB</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="dob"  type="date" value="{{ old('dob') }}" >
                        
                         @error('dob')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Photo</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="photo" id="image" type="file" value="{{ old('photo') }}">
                        
                         @error('photo')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>



                <div class="row mb-3">
                    <label for="example-email-input" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                    <img id="ShowImage" src="{{asset('uploads/no_image.png')}}" alt="avatar-4" class="rounded avatar-md">
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Gender</label>
                    <div class="col-sm-10">

                    <input class="form-check-input" type="radio" name="gender" checked="" value="Male">
                    <label class="form-check-label" for="formRadios1"> Male </label>


                    <input class="form-check-input" type="radio" name="gender" value="Female">
                    <label class="form-check-label" for="formRadios1"> Female </label>
                 
                     @error('gender')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>
                    <!-- end row -->


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">User name</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="username"  type="text" placeholder="User Name" value="{{ old('username') }}">
                       
                         @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>

                <!-- end row -->


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="password" type="password" placeholder="Password" value="{{ old('password') }}">
                        
                         @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>
                 <!-- end row -->


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="email" type="text" placeholder="Email Address" value="{{ old('email') }}">
                        
                         @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>
            

                <!-- end row -->

                <button type="submit" class="btn btn-primary waves-effect waves-light">Add Teacher</button>
                
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



