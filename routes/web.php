<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\ProgramCommitteeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\ProceedingsController;
use App\Http\Controllers\NotificationController;
use App\Models\ProgramCommittee;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramBookController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::get('/conferences/{conference}/chairs', [ConferenceController::class, 'showChairManagement'])
     ->name('conferences.chairs.index')
     ->middleware('auth');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('conferences', ConferenceController::class);

    // Paper routes
    Route::post('/papers/{paper}/request-review', [PaperController::class, 'requestReview'])
        ->name('papers.request-review');
    Route::post('/papers/{paper}/accept-review', [PaperController::class, 'acceptReview'])
        ->name('papers.accept-review');
    Route::post('/papers/{paper}/reject-review', [PaperController::class, 'rejectReview'])
        ->name('papers.reject-review');

    // Conference chair management
    Route::post('/conferences/{conference}/chairs', [ConferenceController::class, 'assignChairs'])
         ->name('conferences.chairs.store');
    Route::post('/conferences/{conference}/program-chairs', [ConferenceController::class, 'assignProgramChairs'])
         ->name('conferences.program-chairs.store');
    // Paper submission routes
    Route::resource('papers', PaperController::class)->except(['create']);
    Route::get('/conferences/{conference}/papers/create', [PaperController::class, 'create'])
     ->name('papers.create');
    Route::post('/conferences/{conference}/papers', [PaperController::class, 'store'])
     ->name('papers.store');
    // PC Member routes
    Route::prefix('conferences/{conference}/pc-members')->group(function () {
        Route::get('/', [ProgramCommitteeController::class, 'index'])->name('pc-members.index');
        Route::post('/', [ProgramCommitteeController::class, 'store'])->name('pc-members.store');
        Route::put('/{pcMember}', [ProgramCommitteeController::class, 'update'])->name('pc-members.update');
        Route::delete('/{pcMember}', [ProgramCommitteeController::class, 'destroy'])->name('pc-members.destroy');
    });
    // Review routes
    Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'edit', 'update', 'store']);
    // Decision routes
    Route::prefix('decisions')->group(function () {
        Route::get('/', [DecisionController::class, 'selectConference'])
            ->name('decisions.select-conference');
        Route::get('/conferences/{conference}', [DecisionController::class, 'index'])
            ->name('decisions.index');
        Route::get('/papers/{paper}', [DecisionController::class, 'show'])
            ->name('decisions.show');
        Route::get('/papers/{paper}/create', [DecisionController::class, 'create'])
            ->name('decisions.create');
        Route::put('/papers/{paper}', [DecisionController::class, 'update'])
            ->name('decisions.update');
    });

    // Proceedings routes
    Route::prefix('proceedings')->middleware(['auth', 'proceedings'])->group(function () {
        Route::get('/', [ProceedingsController::class, 'selectConference'])
            ->name('proceedings.select-conference');
        Route::get('/conferences/{conference}', [ProceedingsController::class, 'index'])
            ->name('proceedings.index');
        Route::get('/conferences/{conference}/create', [ProceedingsController::class, 'create'])
            ->name('proceedings.create');
        Route::post('/conferences/{conference}', [ProceedingsController::class, 'store'])
            ->name('proceedings.store');
        Route::get('/{proceedings}', [ProceedingsController::class, 'show'])
            ->name('proceedings.show');
        Route::get('/{proceedings}/edit', [ProceedingsController::class, 'edit'])
            ->name('proceedings.edit');
        Route::put('/{proceedings}', [ProceedingsController::class, 'update'])
            ->name('proceedings.update');
        Route::get('/{proceedings}/assign-papers', [ProceedingsController::class, 'assignPapers'])
            ->name('proceedings.assign-papers');
        Route::post('/{proceedings}/assign-papers', [ProceedingsController::class, 'storeAssignedPapers'])
            ->name('proceedings.store-assigned-papers');
        Route::delete('/conferences/{conference}/papers/{paper}', [ProceedingsController::class, 'removePaper'])
            ->name('proceedings.remove-paper');
        Route::post('/conferences/{conference}/generate', [ProceedingsController::class, 'generateProceedings'])
            ->name('proceedings.generate');
        Route::get('/conferences/{conference}/download', [ProceedingsController::class, 'downloadProceedings'])
            ->name('proceedings.download');
    });

    // Camera-ready routes
    Route::get('/papers/{paper}/camera-ready', [PaperController::class, 'showCameraReadyForm'])
        ->name('papers.camera-ready-form');
    Route::post('/papers/{paper}/camera-ready', [PaperController::class, 'submitCameraReady'])
        ->name('papers.submit-camera-ready');
    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
        Route::get('/{notification}/redirect', [NotificationController::class, 'redirect'])->name('notifications.redirect');
    });

    // Track invitations
    Route::get('/conferences/{conference}/track-invitations', [ConferenceController::class, 'trackInvitations'])
        ->name('conferences.track-invitations');
    Route::post('/conferences/{conference}/invite-reviewers', [ConferenceController::class, 'inviteReviewers'])
        ->name('conferences.invite-reviewers');
    Route::post('/conferences/{conference}/invitations/{invitation}/resend', [ConferenceController::class, 'resendInvitation'])
        ->name('conferences.resend-invitation');

    // Assign reviewers
    Route::post('/conferences/{conference}/papers/{paper}/assign-reviewers', [PaperController::class, 'assignReviewers'])
        ->name('papers.assign-reviewers')
        ->middleware('auth');
});

// Invitation response routes
Route::get('/pc-invitations/accept/{invitation}', [ProgramCommitteeController::class, 'acceptInvitation'])
    ->name('pc-invitations.accept')
    ->middleware('auth');

Route::get('/pc-invitations/reject/{invitation}', [ProgramCommitteeController::class, 'rejectInvitation'])
    ->name('pc-invitations.reject')
    ->middleware('auth');

// User Management Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Conference Submissions Route
Route::get('/conferences/{conference}/submissions', [ConferenceController::class, 'submissions'])
    ->name('conferences.submissions')
    ->middleware(['auth']);

// Invite Reviewers Route
Route::post('/conferences/{conference}/invite-reviewers', [ConferenceController::class, 'inviteReviewers'])
    ->name('conferences.invite-reviewers')
    ->middleware(['auth']);

Route::middleware(['auth'])->prefix('program-book')->group(function () {
    Route::get('/', [ProgramBookController::class, 'selectConference'])
        ->name('program-book.select-conference');

    Route::get('/conferences/{conference}', [ProgramBookController::class, 'index'])
        ->name('program-book.index');

    Route::get('/create/{conference}', [ProgramBookController::class, 'create'])
        ->name('program-book.create');

    Route::post('/store/{conference}', [ProgramBookController::class, 'store'])
        ->name('program-book.store');

    Route::get('/edit/{programBook}', [ProgramBookController::class, 'edit'])
        ->name('program-book.edit');

    Route::put('/update/{programBook}', [ProgramBookController::class, 'update'])
        ->name('program-book.update');

    Route::get('/manage-sessions/{programBook}', [ProgramBookController::class, 'manageSessions'])
        ->name('program-book.manage-sessions');

    Route::post('/store-session/{programBook}', [ProgramBookController::class, 'storeSession'])
        ->name('program-book.store-session');

    Route::put('/update-session/{session}', [ProgramBookController::class, 'updateSession'])
        ->name('program-book.update-session');

    Route::delete('/delete-session/{session}', [ProgramBookController::class, 'deleteSession'])
        ->name('program-book.delete-session');

    Route::post('/store-presentation/{session}', [ProgramBookController::class, 'storePresentation'])
        ->name('program-book.store-presentation');

    Route::put('/update-presentation/{presentation}', [ProgramBookController::class, 'updatePresentation'])
        ->name('program-book.update-presentation');

    Route::delete('/delete-presentation/{presentation}', [ProgramBookController::class, 'deletePresentation'])
        ->name('program-book.delete-presentation');

    Route::get('/export/{programBook}', [ProgramBookController::class, 'export'])
        ->name('program-book.export');
});

Route::middleware(['auth'])->prefix('reports')->group(function () {
    Route::get('/select-conference', [ReportController::class, 'selectConference'])
        ->name('reports.select-conference');

    Route::get('/{conference}', [ReportController::class, 'index'])
        ->name('reports.index');

    // Conference Details Reports
    Route::get('/{conference}/details', [ReportController::class, 'conferenceDetails'])
        ->name('reports.conference-details');
    Route::get('/{conference}/download/details', [ReportController::class, 'downloadConferenceDetails'])
        ->name('reports.download.conference-details');

    // Review and Decision Reports
    Route::get('/{conference}/reviews', [ReportController::class, 'reviewAndDecisions'])
        ->name('reports.reviews');
    Route::get('/{conference}/download/reviews', [ReportController::class, 'downloadReviewAndDecisions'])
        ->name('reports.download.reviews');

    // Proceedings Reports
    Route::get('/{conference}/proceedings', [ReportController::class, 'proceedings'])
        ->name('reports.proceedings');
    Route::get('/{conference}/download/proceedings', [ReportController::class, 'downloadProceedings'])
        ->name('reports.download.proceedings');

    // Program Book Reports
    Route::get('/{conference}/program-book', [ReportController::class, 'programBook'])
        ->name('reports.program-book');
    Route::get('/{conference}/download/program-book', [ReportController::class, 'downloadProgramBook'])
        ->name('reports.download.program-book');

    // Existing routes
    Route::get('/{conference}/accepted-papers', [ReportController::class, 'acceptedPapers'])
        ->name('reports.accepted-papers');
    Route::get('/{conference}/rejected-papers', [ReportController::class, 'rejectedPapers'])
        ->name('reports.rejected-papers');

    // Download routes
    Route::get('/{conference}/download/submission-stats', [ReportController::class, 'downloadSubmissionStats'])
        ->name('reports.download.submission-stats');

    Route::get('/{conference}/download/review-stats', [ReportController::class, 'downloadReviewStats'])
        ->name('reports.download.review-stats');

    Route::get('/{conference}/download/accepted-papers', [ReportController::class, 'downloadAcceptedPapers'])
        ->name('reports.download.accepted-papers');

    Route::get('/{conference}/download/rejected-papers', [ReportController::class, 'downloadRejectedPapers'])
        ->name('reports.download.rejected-papers');

    Route::get('/{conference}/download/full-report', [ReportController::class, 'downloadFullReport'])
        ->name('reports.download.full-report');
});

require __DIR__.'/auth.php';
