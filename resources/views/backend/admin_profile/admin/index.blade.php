
@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')



@php

$totalstudent = count(App\Models\student::all());
$totalsubject = count(App\Models\subject::all());
$totalclass = count(App\Models\classes::all());
$totalteachers = count(App\Models\teacher::all());

@endphp

<div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Dashboard</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-17 mb-2">Total Student</p>
                                                <h4 class="mb-2">{{$totalstudent}}</h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-primary rounded-3">
                                                    <i class="ri-user-3-line font-size-24"></i>  
                                                </span>
                                            </div>
                                        </div>                                            
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-17 mb-2">Total Subject</p>
                                                <h4 class="mb-2">{{$totalsubject}}</h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-success rounded-3">
                                                    <i class=" fas fa-book-open"></i>  
                                                </span>
                                            </div>
                                        </div>                                              
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-17 mb-2">Total Classes</p>
                                                <h4 class="mb-2">{{$totalclass}}</h4>
                                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2">
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-primary rounded-3">
                                                    <i class="ri-user-3-line font-size-24"></i>  
                                                </span>
                                            </div>
                                        </div>                                              
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            



                        <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-17 mb-2">Total Teachers</p>
                                                <h4 class="mb-2">{{$totalteachers}}</h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-success rounded-3">
                                                    <i class=" fas fa-book-reader"></i>  
                                                </span>
                                            </div>
                                        </div>                                              
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div><!-- end row -->


                        
                    </div>

    @endsection