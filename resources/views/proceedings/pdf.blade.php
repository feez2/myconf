<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $proceedings->title }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', serif; 
            line-height: 1.6; 
            margin: 0; 
            padding: 20px;
        }
        .cover-page { 
            text-align: center; 
            margin-bottom: 50px; 
            page-break-after: always;
        }
        .conference-title { 
            font-size: 28px; 
            font-weight: bold; 
            margin-bottom: 20px;
        }
        .proceedings-title { 
            font-size: 24px; 
            font-weight: bold; 
            margin-bottom: 30px;
        }
        .publication-info { 
            font-size: 16px; 
            margin-bottom: 20px;
        }
        .isbn-issn { 
            font-size: 14px; 
            margin-bottom: 30px;
        }
        .publisher { 
            font-size: 16px; 
            margin-bottom: 40px;
        }
        .publication-date { 
            font-size: 18px; 
            font-weight: bold;
        }
        .toc { 
            page-break-after: always;
        }
        .toc-title { 
            font-size: 24px; 
            font-weight: bold; 
            text-align: center; 
            margin-bottom: 30px;
        }
        .toc-item { 
            margin-bottom: 8px; 
            display: flex; 
            justify-content: space-between;
        }
        .toc-item a { 
            text-decoration: none; 
            color: black;
        }
        .toc-dots { 
            border-bottom: 1px dotted #000; 
            flex-grow: 1; 
            margin: 0 5px;
        }
        .paper { 
            margin-bottom: 40px; 
            page-break-inside: avoid;
        }
        .paper-title { 
            font-size: 18px; 
            font-weight: bold; 
            margin-bottom: 10px;
        }
        .paper-authors { 
            font-size: 14px; 
            font-style: italic; 
            margin-bottom: 15px;
        }
        .paper-abstract { 
            margin-bottom: 15px; 
            text-align: justify;
        }
        .paper-keywords { 
            font-size: 12px; 
            font-style: italic;
        }
        .page-break { 
            page-break-after: always; 
        }
        .section-title { 
            font-size: 20px; 
            font-weight: bold; 
            margin: 30px 0 20px; 
            border-bottom: 2px solid #333; 
            padding-bottom: 5px;
        }
        .front-matter, .back-matter { 
            margin-bottom: 30px; 
            text-align: justify;
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="cover-page">
        @if($proceedings->cover_image)
            <img src="{{ storage_path('app/public/' . $proceedings->cover_image) }}" 
                 style="max-width: 300px; margin-bottom: 30px;">
        @endif
        
        <div class="conference-title">{{ $proceedings->conference->title }}</div>
        <div class="proceedings-title">{{ $proceedings->title }}</div>
        
        <div class="publication-info">
            @if($proceedings->publisher)
                <div class="publisher">{{ $proceedings->publisher }}</div>
            @endif
            @if($proceedings->publication_date)
                <div class="publication-date">{{ $proceedings->publication_date->format('F j, Y') }}</div>
            @endif
        </div>
        
        @if($proceedings->isbn || $proceedings->issn)
            <div class="isbn-issn">
                @if($proceedings->isbn)
                    <div>ISBN: {{ $proceedings->isbn }}</div>
                @endif
                @if($proceedings->issn)
                    <div>ISSN: {{ $proceedings->issn }}</div>
                @endif
            </div>
        @endif
    </div>

    <!-- Table of Contents -->
    <div class="toc">
        <div class="toc-title">Table of Contents</div>
        
        @if($proceedings->front_matter_file)
            <div class="toc-item">
                <span>Front Matter</span>
                <span class="toc-dots"></span>
                <span>i</span>
            </div>
        @endif
        
        @foreach($papers as $index => $paper)
            <div class="toc-item">
                <span>{{ $paper->title }}</span>
                <span class="toc-dots"></span>
                <span>{{ $index + 1 }}</span>
            </div>
        @endforeach
        
        @if($proceedings->back_matter_file)
            <div class="toc-item">
                <span>Back Matter</span>
                <span class="toc-dots"></span>
                <span>{{ $papers->count() + 1 }}</span>
            </div>
        @endif
    </div>

    <!-- Front Matter -->
    @if($proceedings->front_matter_file)
        <div class="page-break"></div>
        <div class="section-title">Front Matter</div>
        <div class="front-matter">
            <!-- Note: In a real implementation, you would need to extract text from the PDF file -->
            <p>Front matter content would be displayed here.</p>
            <p>This could include preface, acknowledgments, committee information, etc.</p>
        </div>
    @endif

    <!-- Papers -->
    @foreach($papers as $index => $paper)
        <div class="page-break"></div>
        <div class="paper">
            <div class="paper-title">{{ $paper->title }}</div>
            <div class="paper-authors">
                @if($paper->authors->count() > 0)
                    {{ $paper->authors->pluck('name')->join(', ') }}
                @else
                    {{ $paper->user->name }}
                @endif
            </div>
            
            <div class="paper-abstract">
                <strong>Abstract:</strong> {{ $paper->abstract }}
            </div>
            
            <div class="paper-keywords">
                <strong>Keywords:</strong> {{ $paper->keywords }}
            </div>
            
            @if($paper->camera_ready_file)
                <div style="margin-top: 20px; font-size: 12px; color: #666;">
                    <em>Camera-ready version available</em>
                </div>
            @endif
        </div>
    @endforeach

    <!-- Back Matter -->
    @if($proceedings->back_matter_file)
        <div class="page-break"></div>
        <div class="section-title">Back Matter</div>
        <div class="back-matter">
            <!-- Note: In a real implementation, you would need to extract text from the PDF file -->
            <p>Back matter content would be displayed here.</p>
            <p>This could include index, appendices, author biographies, etc.</p>
        </div>
    @endif
</body>
</html> 