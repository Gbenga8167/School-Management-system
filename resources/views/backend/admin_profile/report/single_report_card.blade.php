@extends('backend.admin_profile.admin.admin_dashboard')

@section('admin')

@php
    use App\Models\SchoolSetting;
    $schoolSetting = SchoolSetting::first();
@endphp
<style>

     table.deep-border tbody td,
    table.deep-border tbody th{
        border:0.5px solid black;
    }

    .table-responsive{
        position:relative;
    }
    .fixed-header thead th{
        position:sticky;
        top:0;
        z-index:10;
        background-color:#343a40;
        color:#fff;
    }
    
@media print {
    .no-print { display: none !important; }
    body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

     /*add school watermark*/
        body::before{
            content:"";
            position:fixed;
            background: url('{{ $schoolSetting && $schoolSetting->logo ? asset("uploads/logo_images/" . $schoolSetting->logo) : asset("uploads/default.png") }}');
            background-size:cover;
            background-position:top;
            opacity:0.1;
            width:100%;
            height:70%;
            margin-top:300px;
            z-index:-1;
        }

}
</style>

<div class="no-print my-3 text-center">
    <button onclick="window.print()" class="btn btn-dark btn-sm">
        Print This Result
    </button>

    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Back</a>
</div>

<div class="student-report p-4 shadow-sm border rounded">

    {{-- HEADER --}}
    @include('backend.admin_profile.report.report_header', [
        'settings' => $settings,
        'student' => $student,
        'class' => $student->report_class,
        'term' => $term,
        'session' => $session,
        'totals' => $student->score_summary
    ])

    {{-- SUBJECT TABLE --}}
    @include('backend.admin_profile.report.report_subject_table', [
        'results' => $results
    ])

    {{-- PSYCHOMOTOR --}}
    @include('backend.admin_profile.report.psycho_moto_result_table', [
        'psychomotor' => $student->psychomotor
    ])

    {{-- COMMENTS --}}
    <table class="table" style="width:90%;" align="center">
        <tr>
            <td style="width:70%">
                <strong>Class Teacher's Comment :</strong>
                {{ $student->psychomotor->teacher_comment ?? '_______' }}
                <br>

                <strong>Principal's Comment :</strong>
                {{ $student->psychomotor->principal_comment ?? '_______' }}
                <br>

                <strong>Next Term Begins :</strong>
                {{ isset($nextTermBegins)
                    ? \Carbon\Carbon::parse($nextTermBegins)->format('l, jS F, Y')
                    : '________'}}
            </td>

            <td style="width:20%; text-align:center;">
                @if($settings->stamp)
                    <img src="{{ asset('uploads/stamp_images/'.$settings->stamp) }}"
                    width="120" alt="Stamp">
                @endif
            </td>
        </tr>
    </table>

</div>

@endsection
