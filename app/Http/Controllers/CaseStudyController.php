<?php

// app/Http/Controllers/CaseStudyController.php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\CaseStudyDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CaseStudyController extends Controller
{
    public function index()
    {
        $caseStudies = CaseStudy::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('case-studies.index', compact('caseStudies'));
    }

  
    public function show(string $slug)
    {
        $caseStudy = CaseStudy::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        return view('case-studies.show', compact('caseStudy'));
    }
    public function download(Request $request, CaseStudy $caseStudy)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'case_study_id' =>'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
           
        // Record the download
        CaseStudyDownload::create([
           
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
             'case_study_id' => $request->case_study_id,
            'ip_address' => $request->ip(),
        ]);

        // Return the file download
        $filePath = storage_path('app/public/' . $caseStudy->pdf_path);
        return response()->download($filePath, $caseStudy->slug . '.pdf');
    }
}