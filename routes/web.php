<?php


use App\Models\Skill;
use App\Models\User; 
use App\Models\Category;
use App\Models\ProofDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\TeachController;
use App\Http\Controllers\GoogleController;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentSessionController;

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);



Route::get('/skills', [TeachController::class, 'showSkills'])->name('skills.show');

// Default route for the welcome page
Route::get('/', function () {
    return view('welcome');
});
// Registration Routes
//Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'store']); // Add this for the POST request
Route::get('/register', [RegisterController::class, 'create'])->name('register');

// Group routes that require authentication
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        // Route::get('/dashboard', function () {
        //     return view('dashboard');
        // })->name('dashboard');
        Route::get('/dashboard', function () {
            // Eager load the roles for the authenticated $user = Auth::user()->load('roles');user
            // Return the view and pass the user data
            $categories = Category::all();
            $skills=Skill::all();
            $user = User::with('roles')->find(Auth::id());
            $proofDocuments = ProofDocument::all(); 
            // Retrieve the logged-in user's skills and availabilities
    $userSkills = $user->skills->pluck('id');
    $userAvailabilities = $user->availabilities->pluck('id');
    $Alltutors = User::whereHas('roles', function($query) {
        $query->where('RoleName', 'peer-tutor');
    })
    ->get();

 
    $tutors = User::whereHas('proofDocuments', function ($query) use ($userSkills) {
        $query->where('status', 'approved') // ProofDocument must be approved
              ->whereIn('skill_id', $userSkills); // Match user's skills
    })
    
    ->where('id', '!=', Auth::id()) // Exclude the logged-in user
    ->with(['availabilities' => function ($query) {
        $query->distinct('time'); // Ensure distinct availabilities
    }]) // Eager load availabilities
    ->get();
    

            
            return view('dashboard', compact('proofDocuments', 'user', 'categories', 'skills', 'tutors','Alltutors'));

            
        })->name('dashboard');
        Route::get('/admin/skills', [AdminController::class, 'index'])->name('admin.skills.index');
        Route::post('/admin/skills', [AdminController::class, 'store'])->name('admin.skills.store');
        Route::delete('/admin/skills/{id}', [AdminController::class, 'destroy'])->name('admin.skills.destroy');
    
        // Route to show pending proof documents
       // Route::get('/admin/pending-proof-documents', [AdminController::class, 'showPendingProofDocuments'])->name('admin.pending.proof.documents');
        Route::put('/admin/proof-document/{id}', [AdminController::class, 'updateProofDocumentStatus'])->name('admin.proof.update');
    // Route to show all proof documents
    Route::get('/admin/pending-proof-documents', [AdminController::class, 'index'])->name('admin.pending.proof.documents');
    
        // Update the /teach route to use the TeachController
        
        Route::get('/teach', [TeachController::class, 'index'])->name('teach');
Route::put('/teach/session-requests/{id}', [TeachController::class, 'updateSessionRequestStatus'])->name('session-request.update');
        Route::post('/submit-skill-request', [TeachController::class, 'submitSkillRequest'])->name('submit.skill.request');
        Route::post('/tutors/request-session', [LearnController::class, 'requestSession'])->name('tutors.requestSession');
    });
    Route::get('/students/{skillId}', [TeachController::class, 'getStudentsBySkill'])->name('students.by.skill');

    Route::post('/admin/proof/reject', [AdminController::class, 'rejectProof'])->name('admin.proof.reject');

Route::get('/admin/proof-document/view/{id}', [AdminController::class, 'viewDocument'])->name('admin.view.document');
// Route to display the Teach page with rejected skills
Route::get('/teach', [TeachController::class, 'index'])->name('teach');

// Route to handle the update of proof document status (approve/reject)
Route::put('/proof/update/{id}', [TeachController::class, 'updateProofStatus'])->name('proof.update');

Route::put('/session-request/update/{id}', [TeachController::class, 'updateSessionRequestStatus'])->name('session-request.update');
Route::post('/admin/proof/accept', [AdminController::class, 'acceptProof'])->name('admin.proof.accept');
Route::get('/sessions/join/{id}', [SessionController::class, 'joinSession'])->name('sessions.join');
Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');

Route::get('/sessions', [StudentSessionController::class, 'index'])->name('sessions.index');
Route::get('/sessions/join/{id}', [StudentSessionController::class, 'joinSession'])->name('sessions.join');

Route::middleware(['auth'])->group(function () {
    Route::get('/student/sessions', [StudentSessionController::class, 'index'])->name('student.sessions.index');
    Route::get('/student/sessions/join/{id}', [StudentSessionController::class, 'joinSession'])->name('student.sessions.join');
});