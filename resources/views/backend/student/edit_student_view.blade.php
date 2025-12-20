
@extends('backend.admin_profile.admin.admin_dashboard')
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

                                    @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                <h4 class="card-title">Update - Student </h4>
              
                <form action="{{route('update.student')}}" method="post"  enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{$students->id}}">

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Fullname</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="full_name"  type="text" value="{{$students->name}}" >
                       

                         @error('full_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>

            

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Student ID</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="roll_id"  type="text" readonly value="{{$students->roll_id}}" >
                     
                    </div>
                   
                </div>

            

                <!-- Email address -->
                <div class="row mb-3">
                     <label for="email" class="col-sm-2 col-form-label">Email</label>
                     <div class="col-sm-10">
                         <input class="form-control" name="email" type="text" 
                                value="{{ $students->user->email }}">

                                 @error('email')
                        <small class="text-danger">{{ $message }}</small>
                               @enderror
                     </div>
                 </div>
                 
                 <!-- Username -->
                 <div class="row mb-3">
                     <label for="username" class="col-sm-2 col-form-label">Username</label>
                     <div class="col-sm-10">
                         <input class="form-control" name="username" type="text" 
                                value="{{ $students->user->user_name }}">

                                 @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                     </div>
                 </div>
                 
            
                <!-- Password Field with Show/Hide -->
                <div class="row mb-3">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10 position-relative">
                        <input class="form-control" name="password" id="password" type="password"
                               placeholder="Enter new password (leave blank to keep current)">
                
                        <!-- Toggle Eye Icon -->
                        <i class="fa fa-eye-slash" id="togglePassword"
                           style="position: absolute; right: 15px; top: 10px; cursor: pointer;"></i>
                
                        <small class="text-muted">Leave blank if you don't want to change the password.</small>
                    </div>
                </div>

                  
                 <div class="row mb-3">
                     <label for="class_id" class="col-sm-2 col-form-label" style="font-size:15px">Class</label>
                     <div class="col-sm-10">
                         <select name="class_id" class="form-select">
                             <option value="">-- Select Class --</option>
                             @foreach($classes as $class)
                                 <option value="{{ $class->id }}" 
                                     {{ $students->class_id == $class->id ? 'selected' : '' }}>
                                     {{ $class->class_name }}
                                 </option>
                             @endforeach
                         </select>

                          @error('class_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                     </div>
                 </div>

                


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">DOB</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="dob"  type="date"  value="{{$students->dob}}">
                      
                        
                         @error('dob')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>


                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Photo</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="photo" id="image"  type="file">
                        
                         @error('photo')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
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

                    <input class="form-check-input" type="radio" name="gender"  value="male" {{$students->gender == 'male'? 'checked' : ''}} >
                    <label class="form-check-label" for="formRadios1"> Male </label>


                    <input class="form-check-input" type="radio" name="gender" value="female" {{$students->gender == 'female'? 'checked' : ''}}>
                    <label class="form-check-label" for="formRadios1"> Female </label>
                 
                     @error('gender')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
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

                         @error('status')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>

                <!-- end row -->


                <h4 class="card-title" style="text-align:center">Parent Details</h4>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Parent name</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="parent_name"  type="text" value="{{$students->parent_name}}" >
                       

                         @error('parent_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- end row -->

                   
                </div>

                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Parent Occupation</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="parent_occupation"  type="text" value="{{$students->parent_occupation}}">
                      
                         @error('parent_occupation')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>
                <!-- end row -->




                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label">Gender</label>
                    <div class="col-sm-10">

                    <input class="form-check-input" type="radio" name="parent_gender"  value="male" {{$students->parent_gender == 'male'? 'checked' : ''}} >
                    <label class="form-check-label" for="formRadios1"> Male </label>


                    <input class="form-check-input" type="radio" name="parent_gender" value="female" {{$students->parent_gender == 'female'? 'checked' : ''}}>
                    <label class="form-check-label" for="formRadios1"> Female </label>
                 
                     @error('parent_gender')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>
                <!-- end row -->



                <div class="row mb-3">
    <label for="example-text-input" class="col-sm-2 col-form-label">State Of Origin</label>
    <div class="col-sm-10">
        <select name="State_of_origin" class="form-select" aria-label="Default select example">
            <option value="">-- Select State --</option>
            <option value="Abia" {{ $students->state_of_origin == 'Abia' ? 'selected' : '' }}>Abia</option>
            <option value="Adamawa" {{ $students->state_of_origin == 'Adamawa' ? 'selected' : '' }}>Adamawa</option>
            <option value="Akwa Ibom" {{ $students->state_of_origin == 'Akwa Ibom' ? 'selected' : '' }}>Akwa Ibom</option>
            <option value="Anambra" {{ $students->state_of_origin == 'Anambra' ? 'selected' : '' }}>Anambra</option>
            <option value="Bauchi" {{ $students->state_of_origin == 'Bauchi' ? 'selected' : '' }}>Bauchi</option>
            <option value="Bayelsa" {{ $students->state_of_origin == 'Bayelsa' ? 'selected' : '' }}>Bayelsa</option>
            <option value="Benue" {{ $students->state_of_origin == 'Benue' ? 'selected' : '' }}>Benue</option>
            <option value="Borno" {{ $students->state_of_origin == 'Borno' ? 'selected' : '' }}>Borno</option>
            <option value="Cross River" {{ $students->state_of_origin == 'Cross River' ? 'selected' : '' }}>Cross River</option>
            <option value="Delta" {{ $students->state_of_origin == 'Delta' ? 'selected' : '' }}>Delta</option>
            <option value="Ebonyi" {{ $students->state_of_origin == 'Ebonyi' ? 'selected' : '' }}>Ebonyi</option>
            <option value="Edo" {{ $students->state_of_origin == 'Edo' ? 'selected' : '' }}>Edo</option>
            <option value="Ekiti" {{ $students->state_of_origin == 'Ekiti' ? 'selected' : '' }}>Ekiti</option>
            <option value="Enugu" {{ $students->state_of_origin == 'Enugu' ? 'selected' : '' }}>Enugu</option>
            <option value="Gombe" {{ $students->state_of_origin == 'Gombe' ? 'selected' : '' }}>Gombe</option>
            <option value="Imo" {{ $students->state_of_origin == 'Imo' ? 'selected' : '' }}>Imo</option>
            <option value="Jigawa" {{ $students->state_of_origin == 'Jigawa' ? 'selected' : '' }}>Jigawa</option>
            <option value="Kaduna" {{ $students->state_of_origin == 'Kaduna' ? 'selected' : '' }}>Kaduna</option>
            <option value="Kano" {{ $students->state_of_origin == 'Kano' ? 'selected' : '' }}>Kano</option>
            <option value="Katsina" {{ $students->state_of_origin == 'Katsina' ? 'selected' : '' }}>Katsina</option>
            <option value="Kebbi" {{ $students->state_of_origin == 'Kebbi' ? 'selected' : '' }}>Kebbi</option>
            <option value="Kogi" {{ $students->state_of_origin == 'Kogi' ? 'selected' : '' }}>Kogi</option>
            <option value="Kwara" {{ $students->state_of_origin == 'Kwara' ? 'selected' : '' }}>Kwara</option>
            <option value="Lagos" {{ $students->state_of_origin == 'Lagos' ? 'selected' : '' }}>Lagos</option>
            <option value="Nasarawa" {{ $students->state_of_origin == 'Nasarawa' ? 'selected' : '' }}>Nasarawa</option>
            <option value="Niger" {{ $students->state_of_origin == 'Niger' ? 'selected' : '' }}>Niger</option>
            <option value="Ogun" {{ $students->state_of_origin == 'Ogun' ? 'selected' : '' }}>Ogun</option>
            <option value="Ondo" {{ $students->state_of_origin == 'Ondo' ? 'selected' : '' }}>Ondo</option>
            <option value="Osun" {{ $students->state_of_origin == 'Osun' ? 'selected' : '' }}>Osun</option>
            <option value="Oyo" {{ $students->state_of_origin == 'Oyo' ? 'selected' : '' }}>Oyo</option>
            <option value="Plateau" {{ $students->state_of_origin == 'Plateau' ? 'selected' : '' }}>Plateau</option>
            <option value="Rivers" {{ $students->state_of_origin == 'Rivers' ? 'selected' : '' }}>Rivers</option>
            <option value="Sokoto" {{ $students->state_of_origin == 'Sokoto' ? 'selected' : '' }}>Sokoto</option>
            <option value="Taraba" {{ $students->state_of_origin == 'Taraba' ? 'selected' : '' }}>Taraba</option>
            <option value="Yobe" {{ $students->state_of_origin == 'Yobe' ? 'selected' : '' }}>Yobe</option>
            <option value="Zamfara" {{ $students->state_of_origin == 'Zamfara' ? 'selected' : '' }}>Zamfara</option>
            <option value="FCT" {{ $students->state_of_origin == 'FCT' ? 'selected' : '' }}>Federal Capital Territory (Abuja)</option>
        </select>


                       @error('State_of_origin')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
    </div>
</div>

                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Phone Number</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="phone_number"  type="text" value="{{$students->phone_number}}">
                    
                         @error('phone_number')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                </div>
                <!-- end row -->


                <div class="mb-3">
                     <label>Contact Address</label>
                     <div>
                         <textarea name="address" class="form-control" rows="5" style="height: 173px;"  placeholder="Address" >{{$students->address}}</textarea>

                          @error('address')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                     </div>
                 </div>
                 <!-- end row -->

                <div class="row mb-3">
                    <label for="example-text-input"  class="col-sm-2 col-form-label">Nationality</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="nationality"  type="text" value="{{$students->nationality}}">
                    
                         @error('nationality')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
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
  
<script>
    // Password Show/Hide
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

@endsection