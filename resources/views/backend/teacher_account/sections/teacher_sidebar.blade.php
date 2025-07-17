

@php

$id = Auth::user()->id;
$TeacherData = App\Models\User::findOrFail(Auth::user()->id);
$teacherphoto = App\Models\teacher::where('user_id', $id)->first();


@endphp

<div class="vertical-menu">

<div data-simplebar class="h-100">

    <!-- User details -->
    <div class="user-profile text-center mt-3">
        <div class="">
            <img src="{{ empty($teacherphoto->photo)? asset('uploads/no_image.png') : asset('uploads/teachers_photos/'.$teacherphoto->photo)}}"  alt="" class="avatar-md rounded-circle">
        </div>
        <div class="mt-3">
            <h4 class="font-size-16 mb-1">{{$TeacherData->name}}</h4>
            <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>{{$TeacherData->email}}</span>
        </div>
    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title">MAIN CATEGORY</li>

            <li>
                <a href="{{route('teacher.dashboard')}}" class="waves-effect">
                    <i class="ri-dashboard-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>


            <li class="menu-title">APPERANCE</li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-account-circle-line"></i>
                    <span>Assigned Subject</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('teacher.assigned.subject')}}"> Assigned Subject</a></li>
                   
                
                
                </ul>
            </li>

            <li>
                
                @php
                $teacher = auth()->user()->teacher;
                $isClassTeacher = App\Models\classes::where('class_teacher_id', $teacher->id)->exists();
                @endphp

                @if($isClassTeacher)
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-account-circle-line"></i>
                    <span>Psychomotor</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('select.psychomotor')}}"> Psychomotor & comment</a></li>

                </ul>
                @else
           
                <a href="javascript: void(0);" class="has-arrow waves-effect" onclick="alert('Access Denied : You are not authorized to access the psychomotor assessment. Only class teachers can access this section')" >
                    <i class="ri-account-circle-line"></i>
                    <span>Psychomotor</span>
                </a>
                @endif
            </li>



            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="ri-dashboard-line"></i>
                    <span>Results</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('select.result')}}"> Result Upload Online</a></li>
                   
                
                
                </ul>
            </li>


            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="ri-dashboard-line"></i>
                    <span>Profile</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('teacher.profile')}}">View Profile</a></li>

                
                </ul>
            </li>



            <a class="dropdown-item text-danger" href="{{route('teacher.logout')}}">
            <i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>

            

        </ul>
    </div>
    <!-- Sidebar -->
</div>
</div> 