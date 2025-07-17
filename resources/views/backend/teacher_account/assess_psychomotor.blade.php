@extends('backend.teacher_account.teacher_dashboard')
@section('teacher')

<div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">CLASS TEACHER PSYCHOMOTOR | COMMENT</h4>

            

        </div>
    </div>
</div>
<!-- end page title -->


<div class="row">
<form action="{{route('store.psychomotor')}}" method="POST">
                    @csrf 
                    <input type="hidden" name="class_id" value="{{$class->id}}">
                    <input type="hidden" name="term" value="{{$terms}}">
                    <input type="hidden" name="session" value="{{$sessions}}">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Psychomotor Assessments for {{$class->class_name}} - {{$terms}} - {{$sessions}}</h5>

        
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                                             <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Attendance</th>
                                                <th>Punctuality</th>
                                                <th>Neatness</th>
                                                <th>Honesty</th>
                                                <th>Musical</th>
                                                <th>Initiative</th>
                                                <th>Creativity</th>
                                                <th>Sport</th>
                                                <th>Perseverance</th>
                                                <th>Cooperation</th>
                                                <th>Teacher Comment</th>
                                                
                                            
                                            </tr>
                                            </thead>
                                            
                                            <tbody>
                                                @foreach($students as $index => $student)

                                            <tr>

                                               <td><b>{{ $index + 1}}</b></td>

                                                <td><b>{{ $student->name ?? 'Name Missing'}} </b></td>

                                                <input type="hidden" name="assessments[{{ $index }}][student_id]" value ="{{$student->id}}">

                                             @php 
                                            $existing = $existingAssessment[$student->id] ?? null;
                                            @endphp

                                                <td>
                                                    <select  name="assessments[{{ $index}}][attendance]" class="form-select" aria-label="Default select example">
                                                <option value="">----</option>

                                                 @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                <option value="{{$grade}}" {{$existing && $existing->attendance == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                 @endforeach
                                                    
                                                </select></b>
                                            </td>

                                                
                                                <td>
                                                    
                                                    <b> <select  name="assessments[{{ $index}}][punctuality]" class="form-select" aria-label="Default select example">
                                                <option value="">----</option>

                                                 @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                <option value="{{$grade}}" {{$existing && $existing->punctuality == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                 @endforeach
                                                    
                                                </select></b>
                                            </td>


                                                <td>
                                                <b> <select  name="assessments[{{ $index}}][neatness]" class="form-select" aria-label="Default select example">
                                                <option  value="">----</option>

                                                 @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                <option value="{{$grade}}" {{$existing && $existing->neatness == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                 @endforeach
                                                    
                                                </select></b>
                                             </td>


                                                <td>
                                                    
                                                <b> <select  name="assessments[{{ $index}}][honesty]" class="form-select" aria-label="Default select example">
                                                <option value="">----</option>

                                                 @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                <option value="{{$grade}}" {{$existing && $existing->honesty == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                 @endforeach
                                                    
                                                </select></b>
                                                </td>

                                                <td>
                                                    
                                                <b> <select  name="assessments[{{ $index}}][musical]" class="form-select" aria-label="Default select example">
                                                <option value="">----</option>

                                                 @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                <option value="{{$grade}}" {{$existing && $existing->musical == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                 @endforeach
                                                    
                                                </select></b>
                                                </td>


                                                <td>
                                                    
                                                    <b> <select  name="assessments[{{ $index}}][initiative]" class="form-select" aria-label="Default select example">
                                                    <option  value="">----</option>
    
                                                     @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                    <option value="{{$grade}}" {{$existing && $existing->initiative == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                     @endforeach
                                                        
                                                    </select></b>
                                                    </td>


                                                    <td>
                                                    
                                                    <b> <select  name="assessments[{{ $index}}][creativity]" class="form-select" aria-label="Default select example">
                                                    <option value="">----</option>
    
                                                     @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                    <option value="{{$grade}}" {{$existing && $existing->creativity == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                     @endforeach
                                                        
                                                    </select></b>
                                                    </td>



                                                    <td>
                                                    
                                                    <b> <select  name="assessments[{{ $index}}][sport]" class="form-select" aria-label="Default select example">
                                                    <option value="">----</option>
    
                                                     @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                    <option value="{{$grade}}" {{$existing && $existing->sport == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                     @endforeach
                                                        
                                                    </select></b>
                                                    </td>



                                                    <td>
                                                    
                                                    <b> <select  name="assessments[{{ $index}}][perseverance]" class="form-select" aria-label="Default select example">
                                                    <option value="">----</option>
    
                                                     @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                    <option value="{{$grade}}" {{$existing && $existing->perseverance == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                     @endforeach
                                                        
                                                    </select>
                                                    </td>



                                                    <td>
                                                    
                                                    <b> <select  name="assessments[{{ $index}}][cooperation]" class="form-select" aria-label="Default select example">
                                                    <option value="">----</option>
    
                                                     @foreach(['A', 'B', 'C','D', 'E', 'F'] as $grade)
                                                    <option value="{{$grade}}" {{$existing && $existing->cooperation == $grade ? 'selected' : ''}}>{{$grade}}</option>
                                                     @endforeach
                                                        
                                                    </select></b>
                                                    </td>



                                                    <td>
                                                    
                                                    <textarea name="assessments[{{ $index}}][teacher_comment]" class="form-control" rows="5" style="height: 173px;" placeholder="Enter Comment">{{$existing->teacher_comment ?? ''}}</textarea>
                                                    </td>

                                                   


                                            </tr>
                                            

                                                @endforeach

                                            </tr>
                                            </tbody>
                                        </table>

                                        <input type="submit" class="btn btn-primary waves-effect waves-light" value="Submit All Assessments">
                                        </form>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->





@endsection