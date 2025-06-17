<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Paper;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function selectConference()
    {
        $conferences = Conference::has('papers')->get();
        return view('reports.select-conference', compact('conferences'));
    }

    public function index(Conference $conference)
    {
        $stats = [
            'submission' => $conference->submission_stats,
            'review' => $conference->review_stats,
        ];

        return view('reports.index', compact('conference', 'stats'));
    }

    public function acceptedPapers(Conference $conference)
    {
        $papers = $conference->acceptedPapers()
            ->with('authors')
            ->orderBy('title')
            ->get();

        return view('reports.accepted-papers', compact('conference', 'papers'));
    }

    public function rejectedPapers(Conference $conference)
    {
        $papers = $conference->rejectedPapers()
            ->with('authors')
            ->orderBy('title')
            ->get();

        return view('reports.rejected-papers', compact('conference', 'papers'));
    }

    public function downloadSubmissionStats(Conference $conference)
    {
        $stats = $conference->submission_stats;

        $pdf = Pdf::loadView('reports.pdf.submission-stats', [
            'conference' => $conference,
            'stats' => $stats
        ]);

        return $pdf->download("{$conference->acronym}-submission-stats.pdf");
    }

    public function downloadReviewStats(Conference $conference)
    {
        $stats = $conference->review_stats;

        if (!$stats) {
            return back()->with('error', 'No review data available for this conference.');
        }

        $pdf = Pdf::loadView('reports.pdf.review-stats', [
            'conference' => $conference,
            'stats' => $stats
        ]);

        return $pdf->download("{$conference->acronym}-review-stats.pdf");
    }

    public function downloadAcceptedPapers(Conference $conference)
    {
        $papers = $conference->acceptedPapers()
            ->with('authors')
            ->orderBy('title')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.accepted-papers', [
            'conference' => $conference,
            'papers' => $papers
        ]);

        return $pdf->download("{$conference->acronym}-accepted-papers.pdf");
    }

    public function downloadRejectedPapers(Conference $conference)
    {
        $papers = $conference->rejectedPapers()
            ->with('authors')
            ->orderBy('title')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.rejected-papers', [
            'conference' => $conference,
            'papers' => $papers
        ]);

        return $pdf->download("{$conference->acronym}-rejected-papers.pdf");
    }

    public function downloadFullReport(Conference $conference)
    {
        $submissionStats = $conference->submission_stats;
        $reviewStats = $conference->review_stats;
        $acceptedPapers = $conference->acceptedPapers()->with('authors')->orderBy('title')->get();
        $rejectedPapers = $conference->rejectedPapers()->with('authors')->orderBy('title')->get();

        $pdf = Pdf::loadView('reports.pdf.full-report', [
            'conference' => $conference,
            'submissionStats' => $submissionStats,
            'reviewStats' => $reviewStats,
            'acceptedPapers' => $acceptedPapers,
            'rejectedPapers' => $rejectedPapers
        ]);

        return $pdf->download("{$conference->acronym}-full-report.pdf");
    }

    public function conferenceDetails(Conference $conference)
    {
        $details = [
            'basic_info' => [
                'title' => $conference->title,
                'acronym' => $conference->acronym,
                'start_date' => $conference->start_date,
                'end_date' => $conference->end_date,
                'venue' => $conference->venue,
                'website' => $conference->website,
                'description' => $conference->description,
            ],
            'committees' => [
                'program_chairs' => $conference->programCommittees()
                    ->where('role', 'program_chair')
                    ->where('status', 'accepted')
                    ->with('user')
                    ->get(),
                'area_chairs' => $conference->programCommittees()
                    ->where('role', 'area_chair')
                    ->where('status', 'accepted')
                    ->with('user')
                    ->get(),
                'reviewers' => $conference->programCommittees()
                    ->where('role', 'reviewer')
                    ->where('status', 'accepted')
                    ->with('user')
                    ->get(),
            ],
            'important_dates' => [
                'submission_deadline' => $conference->submission_deadline,
                'review_deadline' => $conference->review_deadline,
                'notification_date' => $conference->notification_date,
                'camera_ready_deadline' => $conference->camera_ready_deadline,
            ],
            'statistics' => [
                'total_submissions' => $conference->papers()->count(),
                'accepted_papers' => $conference->papers()->where('status', 'accepted')->count(),
                'rejected_papers' => $conference->papers()->where('status', 'rejected')->count(),
                'total_reviewers' => $conference->programCommittees()->where('status', 'accepted')->count(),
            ]
        ];

        return view('reports.conference-details', compact('conference', 'details'));
    }

    public function downloadConferenceDetails(Conference $conference)
    {
        $details = [
            'basic_info' => [
                'title' => $conference->title,
                'acronym' => $conference->acronym,
                'start_date' => $conference->start_date,
                'end_date' => $conference->end_date,
                'venue' => $conference->venue,
                'website' => $conference->website,
                'description' => $conference->description,
            ],
            'committees' => [
                'program_chairs' => $conference->programCommittees()
                    ->where('role', 'program_chair')
                    ->where('status', 'accepted')
                    ->with('user')
                    ->get(),
                'area_chairs' => $conference->programCommittees()
                    ->where('role', 'area_chair')
                    ->where('status', 'accepted')
                    ->with('user')
                    ->get(),
                'reviewers' => $conference->programCommittees()
                    ->where('role', 'reviewer')
                    ->where('status', 'accepted')
                    ->with('user')
                    ->get(),
            ],
            'important_dates' => [
                'submission_deadline' => $conference->submission_deadline,
                'review_deadline' => $conference->review_deadline,
                'notification_date' => $conference->notification_date,
                'camera_ready_deadline' => $conference->camera_ready_deadline,
            ],
            'statistics' => [
                'total_submissions' => $conference->papers()->count(),
                'accepted_papers' => $conference->papers()->where('status', 'accepted')->count(),
                'rejected_papers' => $conference->papers()->where('status', 'rejected')->count(),
                'total_reviewers' => $conference->programCommittees()->where('status', 'accepted')->count(),
            ]
        ];

        $pdf = Pdf::loadView('reports.pdf.conference-details', [
            'conference' => $conference,
            'details' => $details
        ]);

        return $pdf->download("{$conference->acronym}-conference-details.pdf");
    }

    public function reviewAndDecisions(Conference $conference)
    {
        $papers = $conference->papers()
            ->with(['authors', 'reviews.reviewer', 'user'])
            ->orderBy('title')
            ->get();

        $reviewStats = [
            'total_reviews' => $papers->sum(function($paper) { return $paper->reviews->count(); }),
            'average_reviews_per_paper' => $papers->avg(function($paper) { return $paper->reviews->count(); }),
            'review_completion_rate' => $papers->filter(function($paper) {
                return $paper->reviews->count() >= 2;
            })->count() / $papers->count() * 100,
        ];

        return view('reports.reviews', compact('conference', 'papers', 'reviewStats'));
    }

    public function downloadReviewAndDecisions(Conference $conference)
    {
        $papers = $conference->papers()
            ->with(['authors', 'reviews.reviewer', 'user'])
            ->orderBy('title')
            ->get();

        $reviewStats = [
            'total_reviews' => $papers->sum(function($paper) { return $paper->reviews->count(); }),
            'average_reviews_per_paper' => $papers->avg(function($paper) { return $paper->reviews->count(); }),
            'review_completion_rate' => $papers->filter(function($paper) {
                return $paper->reviews->count() >= 2;
            })->count() / $papers->count() * 100,
        ];

        $pdf = Pdf::loadView('reports.pdf.reviews', [
            'conference' => $conference,
            'papers' => $papers,
            'reviewStats' => $reviewStats
        ]);

        return $pdf->download("{$conference->acronym}-review-and-decisions.pdf");
    }

    public function proceedings(Conference $conference)
    {
        $proceedings = $conference->proceedings()->first();
        $papers = $proceedings ? $proceedings->papers()
            ->where('status', Paper::STATUS_ACCEPTED)
            ->where('approved_for_proceedings', true)
            ->with('authors')
            ->orderBy('title')
            ->get() : collect();

        $stats = [
            'total_papers' => $papers->count(),
            'total_pages' => $papers->sum('pages'),
            'publication_date' => $proceedings ? $proceedings->publication_date : null,
            'isbn' => $proceedings ? $proceedings->isbn : null,
            'issn' => $proceedings ? $proceedings->issn : null,
        ];

        return view('reports.proceedings', compact('conference', 'proceedings', 'papers', 'stats'));
    }

    public function downloadProceedings(Conference $conference)
    {
        $proceedings = $conference->proceedings()->first();
        $papers = $proceedings ? $proceedings->papers()
            ->where('status', Paper::STATUS_ACCEPTED)
            ->where('approved_for_proceedings', true)
            ->with('authors')
            ->orderBy('title')
            ->get() : collect();

        $stats = [
            'total_papers' => $papers->count(),
            'total_pages' => $papers->sum('pages'),
            'publication_date' => $proceedings ? $proceedings->publication_date : null,
            'isbn' => $proceedings ? $proceedings->isbn : null,
            'issn' => $proceedings ? $proceedings->issn : null,
        ];

        $pdf = Pdf::loadView('reports.pdf.proceedings', [
            'conference' => $conference,
            'proceedings' => $proceedings,
            'papers' => $papers,
            'stats' => $stats
        ]);

        return $pdf->download("{$conference->acronym}-proceedings-report.pdf");
    }

    public function programBook(Conference $conference)
    {
        $programBook = $conference->programBook()->first();
        $sessions = $programBook ? $programBook->sessions()
            ->with('presentations')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($session) {
                return $session->start_time->format('Y-m-d');
            }) : collect();

        $stats = [
            'total_sessions' => $programBook ? $programBook->sessions->count() : 0,
            'total_presentations' => $programBook ? $programBook->sessions->sum(function($session) {
                return $session->presentations->count();
            }) : 0,
            'date' => $programBook ? $programBook->date : null,
        ];

        return view('reports.program-book', compact('conference', 'programBook', 'sessions', 'stats'));
    }

    public function downloadProgramBook(Conference $conference)
    {
        $programBook = $conference->programBook()->first();
        $sessions = $programBook ? $programBook->sessions()
            ->with('presentations')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($session) {
                return $session->start_time->format('Y-m-d');
            }) : collect();

        $stats = [
            'total_sessions' => $programBook ? $programBook->sessions->count() : 0,
            'total_presentations' => $programBook ? $programBook->sessions->sum(function($session) {
                return $session->presentations->count();
            }) : 0,
            'date' => $programBook ? $programBook->date : null,
        ];

        $pdf = Pdf::loadView('reports.pdf.program-book', [
            'conference' => $conference,
            'programBook' => $programBook,
            'sessions' => $sessions,
            'stats' => $stats
        ]);

        return $pdf->download("{$conference->acronym}-program-book-report.pdf");
    }
}
