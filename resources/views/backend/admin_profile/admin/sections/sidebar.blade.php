

@php

$adminData = App\Models\User::findOrFail(Auth::user()->id);

@endphp

<div class="vertical-menu">

<div data-simplebar class="h-100">

    <!-- User details -->
    <div class="user-profile text-center mt-3">
        <div class="">
            <img src="{{ empty($adminData->photo)? asset('uploads/no_image.png') : asset('uploads/admin_profile/'.$adminData->photo)}}"  alt="" class="avatar-md rounded-circle">
        </div>
        <div class="mt-3">
            <h4 class="font-size-16 mb-1">{{$adminData->name}}</h4>
            <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>{{$adminData->email}}</span>
        </div>
    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title">MAIN CATEGORY</li>

            <li>
                <a href="{{route('admin.dashboard')}}" class="waves-effect">
                    <i class="ri-dashboard-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>


            <li class="menu-title">APPERANCE</li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-account-circle-line"></i>
                    <span>Classes</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('create.class')}}">Create Classes</a></li>
                    <li><a href="{{route('manage.classes')}}">Manage Classes</a></li>
                    <li><a href="{{route('add.assigned.class.teacher')}}">Assign Classes Teacher</a></li>
                    <li><a href="{{route('manage.assigned.class.teacher')}}">Manage Assigned Class Teacher</a></li>
                    
                    
                    
                
                
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-profile-line"></i>
                    <span>Subject</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('create.subject')}}">Create Subject</a></li>
                <li><a href="{{route('manage.subject')}}">Manage Subject</a></li>
                <li><a href="{{route('add.subject.combination')}}">Add Subject Combination</a></li>
                <li><a href="{{route('manage.subject.combination')}}">Manage Subject Combination</a></li>
    
                </ul>
            </li>


            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-account-circle-line"></i>
                    <span>Students</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('add.student')}}">Add Students</a></li>
                    <li><a href="{{route('manage.student')}}">Manage Students</a></li>
                    <li><a href="{{route('assign.student.class.subject')}}">Assign Student Subject & Class</a></li>
                    <li><a href="{{route('manage.assign.student.class.subject')}}">Manage Assigned Student Subject & Class</a></li>
                  
                
                
                </ul>
            </li>



            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-account-circle-line"></i>
                    <span>Teachers</span>
                </a>

                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('add.teacher')}}">Add Teachers</a></li>
                    <li><a href="{{route('manage.teacher')}}">Manage Teachers</a></li>
                    <li><a href="{{route('assign.teacher.subject')}}">Assign Teacher Subject & Class</a></li>
                    <li><a href="{{route('manage.assign.subject.teacher')}}">Manage Assign Teacher Subject & Class</a></li>
                </ul>
            </li>


            
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-profile-line"></i>
                    <span>Results</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('admin.result.upload')}}">Upload Result Online</a></li>
                <li><a href="{{route('admin.report.card.selection')}}"> Generate Report Cards</a></li>
                </ul>
                
            </li>


            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-profile-line"></i>
                    <span>Academic Calender</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('term.calendar')}}">Next Term Begins</a></li>
                </ul>
                
            </li>


            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-profile-line"></i>
                    <span>Principal Comment</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{route('principal.comment.form')}}">Add Comment</a></li>
                </ul>
                
            </li>


        </ul>
    </div>
    <!-- Sidebar -->
</div>
</div>