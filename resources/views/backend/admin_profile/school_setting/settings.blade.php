@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="color:#fff;">School Settings</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('school.settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- School Name -->
                <div class="mb-3">
                    <label class="form-label fw-bold">School Name</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $setting->name ?? '') }}" required>
                </div>

                <!-- Address -->
                <div class="mb-3">
                     <label>School Address, Phone, Email etc..</label>
                     <div>
                         <textarea name="address" class="form-control" rows="5" style="height: 150px;">{{$setting->address}}</textarea>
                     </div>
                 </div>
                



                <!-- Motto -->
                <div class="mb-3">
                    <label class="form-label fw-bold">School Motto</label>
                    <input type="text" name="motto" class="form-control" value="{{ old('motto', $setting->motto ?? '') }}">
                </div>

                
                <!-- Logo -->
                <div class="mb-3">
                    <label class="form-label fw-bold">School Logo</label>
                    <input type="file" name="logo" class="form-control" id="logoInput">
                    
                    <div class="mt-2">
                        <!-- Default: show current logo if exists -->
                        <img 
                            id="logoPreview"
                            src="{{ !empty($setting->logo) ? asset('uploads/logo_images/'.$setting->logo) : 'https://via.placeholder.com/120x120?text=No+Logo' }}" 
                            alt="School Logo" 
                            class="img-thumbnail" 
                            width="120">
                    </div>
                </div>


                <!-- Stamp -->
                <div class="mb-3">
                    <label class="form-label fw-bold">School Stamp</label>
                    <input type="file" name="stamp" class="form-control" id="stampInput">
                    
                    <div class="mt-2">
                        <img 
                            id="stampPreview"
                            src="{{ !empty($setting->stamp) ? asset('uploads/stamp_images/'.$setting->stamp) : 'https://via.placeholder.com/120x120?text=No+Stamp' }}" 
                            alt="School Stamp" 
                            class="img-thumbnail" 
                            width="120">
                    </div>
                </div>


                <!-- Submit -->
                <button type="submit" class="btn btn-success px-4">
                    {{ $setting ? 'Update Settings' : 'Save Settings' }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Preview Script -->
<script>
document.getElementById('logoInput').addEventListener('change', function(event) {
    let reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('logoPreview').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(event.target.files[0]);
});



document.getElementById('stampInput').addEventListener('change', function(event) {
    let reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('stampPreview').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(event.target.files[0]);
});

</script>

@endsection
