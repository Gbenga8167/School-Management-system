<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>LOGIN PAGE </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('../BackendTem/assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('../BackendTem/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('../BackendTem/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('../BackendTem/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body class="auth-body-bg">
        <div class="bg-overlay"></div>
        <div class="wrapper-page">
            <div class="container-fluid p-0">
                <div class="card">
                    <div class="card-body">

                    
                     <div class="text-center mt-4">
                            <h3 class="text-muted text-center"> My School Portal</h3>
                            

                        </div>
                     
    
                        <h1 class="text-muted text-center font-size-20"> Log In </h1>
    
                        <div class="p-3">
                        
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group mb-3 row">
                                    <div class="col-12">
                                        <input class="form-control  @error('user_name') is-invalid @enderror" type="text"   name="user_name" 
                                        :value="old('user_name')" 
                                        placeholder="Username">
                                        @error('user_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
    
                                <div class="form-group mb-3 row">
                                    <div class="col-12">
                                        <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" 
                                        :value="old('Password')" placeholder="Password">
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
    
                                <div class="form-group mb-3 row">
                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="form-label ms-1" for="customCheck1">Remember me</label>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="form-group mb-3 text-center row mt-3 pt-1">
                                    <div class="col-12">
                                        <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </div>
    
                                <div class="form-group mb-0 row mt-2">
                                    <div class="col-sm-7 mt-3">
                                        <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                    </div>
                                    <div class="col-sm-5 mt-3">
                                        <a href="auth-register.html" class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a>
                                    </div>
                                </div>

                               
                            </form>
                        </div>
                        <!-- end -->
                    </div>
                    <!-- end cardbody -->
                </div>
                <!-- end card -->
            </div>
            <!-- end container -->
        </div>
        <!-- end -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('../BackendTem/assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('../BackendTem/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('../BackendTem/assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('../BackendTem/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('../BackendTem/assets/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{asset('../BackendTem/assets/js/app.js')}}"></script>

    </body>
</html>







