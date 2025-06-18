<?php

namespace App\Http\Controllers;

use App\Models\Proceedings;
use App\Models\Conference;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ProceedingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function selectConference()
    {
        $this->authorize('viewAny', Proceedings::class);

        $conferences = Conference::orderBy('title')->get();
        return view('proceedings.select-conference', compact('conferences'));
    }

    public function index(Conference $conference)
    {
        try {
            $this->authorize('manageProceedings', $conference);

            Log::info('Accessing proceedings index', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'conference_id' => $conference->id
            ]);

            $proceedings = $conference->proceedings()
                ->with(['papers.author'])
                ->first();

            if (!$proceedings) {
                $proceedings = $conference->proceedings()->create([
                    'title' => $conference->title . ' Proceedings',
                    'status' => 'draft'
                ]);
            }

            // Get accepted papers that are not yet in proceedings
            $acceptedPapers = $conference->papers()
                ->where('status', 'accepted')
                ->whereDoesntHave('proceedings')
                ->with(['author', 'authors'])
                ->get();

            // Get papers that are already in proceedings
            $includedPapers = $proceedings->papers()
                ->with(['author', 'authors'])
                ->get();

            Log::info('Retrieved papers for proceedings', [
                'conference_id' => $conference->id,
                'accepted_papers_count' => $acceptedPapers->count(),
                'included_papers_count' => $includedPapers->count()
            ]);

            return view('proceedings.index', compact('conference', 'proceedings', 'acceptedPapers', 'includedPapers'));
        } catch (\Exception $e) {
            Log::error('Error in proceedings index', [
                'user_id' => auth()->id(),
                'conference_id' => $conference->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function create(Conference $conference)
    {
        $this->authorize('manageProceedings', $conference);

        return view('proceedings.create', compact('conference'));
    }

    public function store(Request $request, Conference $conference)
    {
        $this->authorize('manageProceedings', $conference);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'issn' => 'nullable|string|max:50',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'front_matter' => 'nullable|file|mimes:pdf|max:10240',
            'back_matter' => 'nullable|file|mimes:pdf|max:10240',
            'cover_image' => 'nullable|image|mimes:jpeg,png|max:2048'
        ]);

        $proceedings = new Proceedings($validated);
        $proceedings->conference_id = $conference->id;

        // Handle file uploads
        if ($request->hasFile('front_matter')) {
            $proceedings->front_matter_file = $request->file('front_matter')->store('proceedings/front-matter', 'public');
        }

        if ($request->hasFile('back_matter')) {
            $proceedings->back_matter_file = $request->file('back_matter')->store('proceedings/back-matter', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $proceedings->cover_image = $request->file('cover_image')->store('proceedings/covers', 'public');
        }

        $proceedings->save();

        return redirect()->route('proceedings.show', $proceedings)
            ->with('success', 'Proceedings created successfully!');
    }

    public function show(Proceedings $proceedings)
    {
        $this->authorize('view', $proceedings);

        $proceedings->load(['conference', 'papers.author']);

        return view('proceedings.show', compact('proceedings'));
    }

    public function edit(Proceedings $proceedings)
    {
        $this->authorize('manageProceedings', $proceedings->conference);

        return view('proceedings.edit', compact('proceedings'));
    }

    public function update(Request $request, Proceedings $proceedings)
    {
        $this->authorize('manageProceedings', $proceedings->conference);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'issn' => 'nullable|string|max:50',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'front_matter' => 'nullable|file|mimes:pdf|max:10240',
            'back_matter' => 'nullable|file|mimes:pdf|max:10240',
            'cover_image' => 'nullable|image|mimes:jpeg,png|max:2048'
        ]);

        // Handle file uploads
        if ($request->hasFile('front_matter')) {
            // Delete old file if exists
            if ($proceedings->front_matter_file) {
                Storage::disk('public')->delete($proceedings->front_matter_file);
            }
            $proceedings->front_matter_file = $request->file('front_matter')->store('proceedings/front-matter', 'public');
        }

        if ($request->hasFile('back_matter')) {
            // Delete old file if exists
            if ($proceedings->back_matter_file) {
                Storage::disk('public')->delete($proceedings->back_matter_file);
            }
            $proceedings->back_matter_file = $request->file('back_matter')->store('proceedings/back-matter', 'public');
        }

        if ($request->hasFile('cover_image')) {
            // Delete old file if exists
            if ($proceedings->cover_image) {
                Storage::disk('public')->delete($proceedings->cover_image);
            }
            $proceedings->cover_image = $request->file('cover_image')->store('proceedings/covers', 'public');
        }

        $proceedings->update($validated);

        return redirect()->route('proceedings.show', $proceedings)
            ->with('success', 'Proceedings updated successfully!');
    }

    public function generate(Proceedings $proceedings)
    {
        $this->authorize('manageProceedings', $proceedings->conference);

        // TODO: Implement actual proceedings generation
        // This would typically use a PDF generation library like TCPDF or DOMPDF
        // to combine all papers with front and back matter

        return redirect()->route('proceedings.show', $proceedings)
            ->with('success', 'Proceedings generation started! You will be notified when complete.');
    }

    public function assignPapers(Proceedings $proceedings)
    {
        $this->authorize('manageProceedings', $proceedings->conference);

        $papers = $proceedings->conference->papers()
            ->where('status', Paper::STATUS_ACCEPTED)
            ->whereNull('proceedings_id')
            ->with('author')
            ->get();

        return view('proceedings.assign-papers', compact('proceedings', 'papers'));
    }

    public function storeAssignedPapers(Request $request, Proceedings $proceedings)
    {
        $this->authorize('manageProceedings', $proceedings->conference);

        $request->validate([
            'paper_ids' => 'required|array',
            'paper_ids.*' => 'exists:papers,id'
        ]);

        // Update the proceedings_id for all selected papers
        Paper::whereIn('id', $request->paper_ids)
            ->update(['proceedings_id' => $proceedings->id]);

        return redirect()->route('proceedings.show', $proceedings)
            ->with('success', 'Papers assigned to proceedings successfully!');
    }

    /**
     * Generate proceedings for a conference
     */
    public function generateProceedings(Conference $conference)
    {
        $this->authorize('manageProceedings', $conference);

        // Get or create proceedings
        $proceedings = $conference->proceedings()->first();
        
        if (!$proceedings) {
            // Create new proceedings if they don't exist
            $proceedings = $conference->proceedings()->create([
                'title' => "{$conference->title} Proceedings",
                'status' => Proceedings::STATUS_DRAFT,
                'publication_date' => now(),
            ]);

            // Get all accepted papers and associate them
            $acceptedPapers = $conference->papers()
                ->where('status', 'accepted')
                ->orderBy('title')
                ->get();

            foreach ($acceptedPapers as $paper) {
                $paper->update(['proceedings_id' => $proceedings->id]);
            }
        }

        // Get papers for the proceedings
        $papers = $proceedings->papers()
            ->where('status', 'accepted')
            ->with(['author', 'authors'])
            ->orderBy('title')
            ->get();

        if ($papers->isEmpty()) {
            return redirect()->route('proceedings.show', $proceedings)
                ->with('error', 'No accepted papers found for these proceedings.');
        }

        try {
            // Generate PDF using the template
            $pdf = Pdf::loadView('proceedings.pdf', [
                'proceedings' => $proceedings,
                'papers' => $papers
            ]);

            // Set PDF options
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isCssFloatEnabled' => true,
                'defaultFont' => 'serif',
                'dpi' => 150,
                'fontHeightRatio' => 0.9,
            ]);

            // Generate unique filename
            $filename = 'proceedings_' . $conference->id . '_' . time() . '.pdf';
            $filepath = 'proceedings/generated/' . $filename;

            // Ensure the directory exists
            Storage::disk('public')->makeDirectory('proceedings/generated');

            // Save PDF to storage
            Storage::disk('public')->put($filepath, $pdf->output());

            // Update proceedings with generated PDF file path
            $proceedings->update([
                'generated_pdf_file' => $filepath,
                'status' => Proceedings::STATUS_PUBLISHED
            ]);

            Log::info('Proceedings PDF generated successfully', [
                'conference_id' => $conference->id,
                'proceedings_id' => $proceedings->id,
                'filepath' => $filepath,
                'papers_count' => $papers->count()
            ]);

            return redirect()->route('proceedings.show', $proceedings)
                ->with('success', 'Proceedings PDF generated successfully!');

        } catch (\Exception $e) {
            Log::error('Error generating proceedings PDF', [
                'conference_id' => $conference->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('proceedings.show', $proceedings)
                ->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download proceedings PDF for a conference
     */
    public function downloadProceedings(Conference $conference)
    {
        $this->authorize('manageProceedings', $conference);

        $proceedings = $conference->proceedings()->first();
        
        if (!$proceedings || !$proceedings->generated_pdf_file) {
            return redirect()->route('proceedings.index', $conference)
                ->with('error', 'No generated PDF found for these proceedings.');
        }

        if (!Storage::disk('public')->exists($proceedings->generated_pdf_file)) {
            return redirect()->route('proceedings.index', $conference)
                ->with('error', 'Generated PDF file not found.');
        }

        return Storage::disk('public')->download($proceedings->generated_pdf_file);
    }

    /**
     * Remove a paper from proceedings
     */
    public function removePaper(Conference $conference, Paper $paper)
    {
        $this->authorize('manageProceedings', $conference);

        // Check if the paper belongs to this conference
        if ($paper->conference_id !== $conference->id) {
            return redirect()->route('proceedings.index', $conference)
                ->with('error', 'Paper does not belong to this conference.');
        }

        // Remove the paper from proceedings
        $paper->update(['proceedings_id' => null]);

        return redirect()->route('proceedings.index', $conference)
            ->with('success', 'Paper removed from proceedings successfully.');
    }
}
