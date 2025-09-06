<?php

use App\Models\TermCalendar;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\ResultController;
use App\Http\Controllers\backend\CBTTestController;
use App\Http\Controllers\backend\ClassesController;
use App\Http\Controllers\backend\StudentController;
use App\Http\Controllers\backend\SubjectController;
use App\Http\Controllers\backend\TeacherController;
use App\Http\Controllers\backend\ClearanceController;
use App\Http\Controllers\backend\ReportCardController;
use App\Http\Controllers\backend\AdminResultController;
use App\Http\Controllers\backend\TermCalendarController;
use App\Http\Controllers\backend\PrincipalCommentController;
use App\Http\Controllers\backend\StudentAccount\StudentCBTController;
use App\Http\Controllers\backend\StudentAccount\StudentAccountController;
use App\Http\Controllers\TeacherBackendController\TeacherAccountController;
use App\Http\Controllers\TeacherBackendController\TeacherPsychomotorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Show login page if guest
Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

// If logged in, redirect to correct dashboard
Route::get('/', function () {
    $user = auth()->user();

    if ($user->role == 1) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role == 2) {
        return redirect()->route('teacher.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
})->middleware('auth');


/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

*/

//student Route for student dashboard
Route::get('/student/dashboard', function () {
    return view('backend.student_account.student_index');
})->middleware(['auth', 'verified', 'students'])->name('student.dashboard');



//Start Student All Route  
    Route::middleware(['auth', 'students'])->group(function(){

    Route::controller(StudentAccountController::class)->group(function(){
    Route::get('student/logout','StudentLogout')->name('student.logout');
    Route::get('student/profile','StudentProfile')->name('student.profile');
    Route::get('student/result/form','StudentResultForm')->name('student.result.form');
    Route::post('student/result/view','StudentResultView')->name('student.result.view');
    Route::get('student/subjects','StudentSubjects')->name('student.subjects');
});

//Student All Route FOR CBTTEST
    Route::controller(StudentCBTController::class)->group(function(){
    Route::get('/student/cbt-tests','Index')->name('student.index');
    Route::get('student/cbt/test/{id}','StartTest')->name('student.cbt.test');
    Route::get('student/cbt/begin/{id}','BeginTest')->name('student.begin.test');
    Route::post('student/cbt/save-answer/{attemptId}/{questionId}', 'saveAnswer')->name('student.cbt.save.answer');
    Route::post('student/cbt/submit/{attemptId}', 'submitTest')->name('student.cbt.submit');

     });

    }); //end Student Route
    










//Start Teacher Route

Route::get('/teacher/dashboard', function () {
    return view('backend.teacher_account.teacher_index');
})->middleware(['auth', 'verified','teachers'])->name('teacher.dashboard');

//Teacher All Route
    Route::middleware(['auth', 'teachers'])->group(function(){
    Route::controller(TeacherAccountController::class)->group(function(){
    Route::get('teacher/logout','TeacherLogout')->name('teacher.logout');
    Route::get('teacher/profile','TeacherProfile')->name('teacher.profile');
    Route::post('teacher/profile/update','TeacherProfileUpdate')->name('teacher.profile.update');
    Route::get('teacher/password/change','TeacherPasswordChange')->name('teacher.password.change');
    Route::post('teacher/password/update','TeacherPasswordUpdate')->name('teacher.password.update');

    //TEACHER ASSIGNED SUBJECT ROUTE

    Route::get('teacher/assigned/subjects','TeacherAssignedSubject')->name('teacher.assigned.subject');

});


//Teacher Pyschomotor All Route

    Route::controller(TeacherPsychomotorController::class)->group(function(){
    Route::get('select/psychomotor','showSelectedForm')->name('select.psychomotor');
    Route::get('load/psychomotor','LoadStudent')->name('load.psychomotor');
    Route::post('store/psychomotor','StorePsychomotor')->name('store.psychomotor');

    
});



   //ALL TEACHERS RESULT UPLOAD ROUTE

    Route::controller(ResultController::class)->group(function(){
    Route::get('select/result','showSelectedForm')->name('select.result');
    Route::get('load/result','LoadResult')->name('load.result');
    Route::post('teacher/results/store','StoreResults')->name('teacher.result.store');
  
    //Ajax Auto Result Save Per Row
    //Route::post('teacher/results/save-row','SaveResultRow')->name('teacher.results.saveRow');
  

    //Ajax GET SUBJECT IN SELECT FORM FOR RESULT
       Route::get('/teacher/get-subjects','getSubjectByClass')->name('teacher.getSubjectByClass');
   
});



// TEACHER'S CBT CONTROLLER
//cbt teacher test create
    Route::controller(CBTTestController::class)->group(function(){
    Route::get('teacher/cbt-tests/create','CBTCreate')->name('cbt.test.create');
    Route::post('teacher/cbt-tests/store','CBTStore')->name('cbt.test.store');
    Route::get('teacher/cbt-tests/index','Index')->name('cbt.test.index');
    //create the question and this cbt.questions.create is used to display question assigned to the teacher 
    //in the cbt_index.blade
    Route::get('teacher/cbt-tests/question/create/{cbtTest}','CreateQuestions')->name('cbt.questions.create');

     //Store the CBT question
     Route::post('teacher/cbt-tests/question/create/{cbtTest}','StoreQuestions')->name('cbt.questions.store');
      
     //Teacher Cbt EDIT, UPDATE AND DELETE CBTQUESTION
     Route::get('/cbt/questions/{id}/edit','edit')->name('cbt.questions.edit');
     Route::delete('/cbt/delete-all-cbt-test-questions/{id}/delete','DestroyAllCBTTestQuestion')->name('cbt.questions.delete.all');
     
//Teacher Cbt EDIT, UPDATE AND DELETE SPECIFIC CBTQUESTION
     Route::get('/cbt/specific/questions/{id}/edit','EditSpecificQuestion')->name('update.specific.questions');
     Route::post('/cbt/questions/{id}/update','update')->name('cbt.questions.update');
     Route::delete('/cbt/questions/{id}/delete','destroy')->name('cbt.questions.delete');



     //TEACHER CBT RESULT CHECK
     // ===============================
// Teacher CBT Results Routes
// =============================
    // Show the CBT Results filter form
    Route::get('/cbt-results', 'results')->name('teacher.cbt.results.form');
    // Fetch and display CBT Results
    Route::post('/cbt-results/fetch','fetchCBTResults')->name('teacher.cbt.results.fetch');
    // Allow a student to retake test
    Route::post('/cbt-results/retake/{student_id}/{cbt_id}', 'retakeTest')->name('teacher.cbt.results.retake');
    Route::post('/teacher/cbt-results/{attempt}/retake', 'retake')->name('teacher.cbt.retake');


    });

      });// end teacher route






      // Start Admin All Route
    //Admin Dashbord Login Route
    Route::get('/admin/dashboard', function () {
    return view('backend.admin_profile.admin.index');
})->middleware(['auth', 'verified', 'admin'])->name('admin.dashboard');


    //Admin All Route
     Route::middleware(['auth', 'admin'])->group(function(){
    Route::controller(AdminController::class)->group(function(){
    Route::get('admin/logout','AdminLogout')->name('admin.logout');
    Route::get('admin/profile','AdminProfile')->name('admin.profile');
    Route::post('admin/profile/update','AdminProfileUpdate')->name('admin.profile.update');
    Route::get('admin/password/change','AdminPasswordChange')->name('admin.password.change');
    Route::post('admin/password/update','AdminPasswordUpdate')->name('admin.password.update');

});


    //ALL CREATE/ADD CLASSES ROUTE URL
    Route::controller(ClassesController::class)->group(function(){
    Route::get('create/classes','CreateClasses')->name('create.class');
    Route::post('store/classes','StoreClasses')->name('store.classes');
    Route::get('manage/classes','ManageClasses')->name('manage.classes');
    Route::get('edit/class/{id}','EditClass')->name('edit.class');
    Route::post('update/class','UpdateClass')->name('update.class');
    Route::get('delete/class/{id}','DeleteClass')->name('delete.class');


    //ADMIN ASSIGNED CLASS TEACHER 
    Route::get('add/assigned/class/teacher','AssignedClassTeacher')->name('add.assigned.class.teacher');
    Route::post('store/assigned/class/teacher','StoreAssignedClassTeacher')->name('store.assigned.class.teacher');
    Route::get('manage/assigned/class/teacher','ManageAssignedClassTeacher')->name('manage.assigned.class.teacher');
    Route::delete('remove/assigned/class/teacher/{class}','RemoveAssignedClassTeacher')->name('remove.assigned.class.teacher');
});




    //ALL CREATE/ADD SUBJECT ROUTE
    Route::controller(SubjectController::class)->group(function(){
    Route::get('create/subject','CreateSubject')->name('create.subject');
    Route::post('store/subject','StotreSubject')->name('store.subject');
    Route::get('manage/subject','ManageSubject')->name('manage.subject');
    Route::get('edit/subject/{id}','EditSubject')->name('edit.subject');
    Route::post('update/subject','UpdateSubject')->name('update.subject');
    Route::get('delete/subject/{id}','DeleteSubject')->name('delete.subject');


    

    // Subject Combination All Route
    Route::get('add/subject/combination','AddSubjectCombination')->name('add.subject.combination');
    Route::post('store/subject/combination','StoreSubjectCombination')->name('store.subject.combination');
    Route::get('manage/subject/combination','ManageSubjectCombination')->name('manage.subject.combination');
    Route::get('deactivate/subject/combination/{id}','DeactivateSubjectCombination')->name('deactivate.subject.combination');
    


    
});





    //STUDENT ALL ROUTE
    Route::controller(StudentController::class)->group(function(){
    Route::get('add/student','AddStudent')->name('add.student');
    Route::post('store/student','StoreStudent')->name('store.student');
    Route::get('manage/student','ManageStudent')->name('manage.student');
    Route::get('edit/student/{id}','EditStudent')->name('edit.student');
    Route::post('update/student','UpdateStudent')->name('update.student');
    Route::get('delete/student/{id}','DeleteStudent')->name('delete.student');

    // ASSIGN STUDENT CLASS SUBJECT
    Route::get('assign/student/class/subject', 'AssignStudentClassSubject')->name('assign.student.class.subject');
    Route::post('store/student/class/subject', 'StoreStudentClassSubject')->name('store.student.class.subject');
    Route::get('manage/assign/student/class/subject', 'ManageAssignStudentClassSubject')->name('manage.assign.student.class.subject');
    Route::get('edit/assign/student/class/subject/{id}', 'EditAssignStudentClassSubject')->name('edit.assign.student.class.subject');
    Route::post('update/assign/student/class/subject', 'UpdateAssignStudentClassSubject')->name('update.assign.student.class.subject');
    Route::get('delete/assign/student/class/subject/{id}', 'DeleteAssignStudentClassSubject')->name('delete.assign.student.class.subject');
    

    
     //Ajax All Request For Assign Subject To Teacher
     Route::get('fetch/student','FetchStudent')->name('fetch.student');
    
});



//TEACHERS ALL ROUTE
    Route::controller(TeacherController::class)->group(function(){
    Route::get('add/teacher','AddTeacher')->name('add.teacher');
    Route::post('store/teacher','StoreTeacher')->name('store.teacher');
    Route::get('manage/teacher','ManageTeacher')->name('manage.teacher');
    Route::get('edit/teacher/{id}','EditTeacher')->name('edit.teacher');
    Route::post('update/teacher','UpdateTeacher')->name('update.teacher');
    Route::get('delete/teacher/{id}','DeleteTeacher')->name('delete.teacher');


    //Add Assign Subject To Teacher
    Route::get('assign/teacher/subject', 'AssignSubjectTeacher')->name('assign.teacher.subject');
    Route::post('store/teacher/subject', 'StoreAssignSubjectTeacher')->name('store.teacher.subject');
    Route::get('manage/assign/subject/teacher', 'ViewAssignSubjectTeacher')->name('manage.assign.subject.teacher');
    Route::get('edit/assign/subject/teacher/{id}', 'EditAssignSubjectTeacher')->name('edit.assign.subject.teacher');
    Route::post('update/assign/subject/teacher', 'UpdateAssignSubjectTeacher')->name('update.assign.subject.teacher');
    Route::get('delete/assign/subject/teacher/{id}', 'DeleteAssignSubjectTeacher')->name('delete.assign.subject.teacher');

    
    //Ajax All Request For Assign Subject To Teacher
    Route::get('fetch/student','FetchStudent')->name('fetch.student');

});



    //ADMIN UPLOAD RESULTS ROUTE
    Route::controller(AdminResultController::class)->group(function(){ 
    Route::get('admin/result/upload','showSelectedAdminForm')->name('admin.result.upload');
    Route::get('load/admin/result','LoadAdminResultsTable')->name('load.admin.result');
    Route::post('store/admin/result','StoreAdminResultsTable')->name('store.admin.result');

    });

    Route::controller(ReportCardController::class)->group(function(){
    Route::get('admin/report-card/select','ShowReportSelectForm')->name('admin.report.card.selection');
    Route::get('admin/report-card','Index')->name('admin.report.card');
    
  
});


//Academic Calender NEXT TERM BEGINS 
    Route::controller(TermCalendarController::class)->group(function(){
    Route::get('term-calendar','TermCalendar')->name('term.calendar');
    Route::Post('store-term-calendar','StoreTermCalendar')->name('store.term.calendar');
    Route::get('edit/term-calendar/{id}','EditTermCalendar')->name('edit.term.calendar');
    Route::delete('delete/term-calendar/{id}','destroy')->name('delete.term.calendar');
});


//Admin add principal comment

    Route::controller(PrincipalCommentController::class)->group(function(){
    Route::get('admin/comment/form','PrincipalCommentForm')->name('principal.comment.form');
    Route::get('admin/comment/load','PrincipalCommentLoad')->name('principal.comment.load');
    Route::post('admin/comment/submit','PrincipalCommentSubmit')->name('principal.comment.submit');
});


//Admin clear and print student results
    Route::controller(ClearanceController::class)->group(function(){
    Route::post('/admin/clearance/toggle/{student}','toggleClearance');
    Route::post('/admin/clearance/clear-all','clearAll');
    
});
    
});// End Admin Route




    //THIS IS THE DEFAULT ROUTE
    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
