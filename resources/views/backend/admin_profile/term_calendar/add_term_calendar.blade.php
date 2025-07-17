@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')

<div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Academic | Calendar </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Academic</a></li>
                    <li class="breadcrumb-item active">Calendar</li>
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

                <h4 class="card-title">Next Term Begins </h4>

                @if(session('error'))
                <div class="alart alert-danger" style="padding:10px 5px; margin-buttom:15px">
                    {{session('error')}}
                </div>
                 @endif
              
                <form action="{{route('store.term.calendar')}}" method="post">
                  @csrf

                  @if(isset($record))
                  
                    <input type="hidden" name="id" value="{{$record->id}}">
                
                  @endif
                    
                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Session</label>
                    <div class="col-sm-10">
                    <input type="text" name="session" value="{{old('session', $record->session ?? '')}}" class="form-control" required placeholder="Enter Academic Session">
                    </div>
                   
                </div> 
                <!-- end row -->



                <div class="row mb-3">
                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Term</label>
                    <div class="col-sm-10">
                    <select  name="term" required class="form-select" aria-label="Default select example">
                       
                        <option  value="">-- Select Term --</option>
                        <option value="first_term" {{(old('term', $record->term ?? '') == 'first_term') ? 'selected' : '' }}>First Term</option>
                        <option value="second_term" {{(old('term', $record->term ?? '') == 'second_term') ? 'selected' : '' }}>Second Term</option>
                        <option value="third_term"  {{(old('term', $record->term ?? '') == 'third_term') ? 'selected' : '' }}>Third Term</option>                         
                    </select>
                    </div>
                   
                </div>
                <!-- end row -->


                <div class="row mb-3">

                    <label for="example-text-input" class="col-sm-2 col-form-label" style="font-size:15px">Next Term Begins</label>
                    <div class="col-sm-10">
                    <input type="date" name="next_term_begins" value="{{old('next_term_begins',isset($record)? $record->next_term_begins->format('Y-m-d') : '')}}" class="form-control" required>
                    </div>
                   
                </div>


                <button type="submit" class="btn btn-primary waves-effect waves-light">{{isset($record)? 'Update' : 'Save'}} </button>

                @if(isset($record)) 
                <a href="{{route('term.calendar')}}" class="btn btn-secondary">Cancel</a>
                @endif
                
                </form>

               
              
            </div>
            
        </div>
    </div> 
</div>


<div class="table-responsive" style="max-height:400px; overflow:auto"> 
<table class="table table-bordered">
    <thead class="table-dark text-center">
        <tr>
            <th>Session</th>
            <th>Term</th>
            <th>Next Term Begins</th>
            <th>Action</th>
        </tr>

    </thead>
    <tbody class="text-center">
        @forelse($records as $rec)
        <tr>
            <td>
                {{$rec->session}}
            </td>

            <td>
                {{strtoupper($rec->term)}}
            </td>

            <td>
                {{$rec->next_term_begins->format('l, jS F, Y')}}.
            </td>
            
            <td>
            <a href="{{route('edit.term.calendar', $rec->id)}}" class="btn btn-primary">Edit</a>
            <form action="{{route('delete.term.calendar', $rec->id)}}" method="post" class="d-inline" onsubmit="return confirm('Delete this entry?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
            
            </td>
        </tr>
        @empty

    
        <tr>
            <td colspan="3">No dates Yet</td>
        </tr>
        @endforelse
        </tbody>
</table>
@endsection



