<?php

namespace App\Http\Controllers;

use App\Models\JobDetails;
use App\Models\Resume;
use App\Models\ResumeScore;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

class JobDetailsController extends Controller
{
    public function index()
    {
        return view('job.create');
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $job = JobDetails::create($request->only(['title', 'description']));

        return redirect()->route('resume.upload', $job->id);
    }

    public function upload(JobDetails $job)
    {
        return view('resume.upload', compact('job'));
    }
    
    // public function storeResume(Request $request)
    // {
    //     $request->validate([
    //         'resume' => 'required|file|mimes:pdf,docx,txt|max:2048',
    //         'job_id' => 'required|exists:job_details,id',
    //     ]);

    //     // Store resume file
    //     $file = $request->file('resume');
    //     $path = $file->store('resumes');

    //     $resume = Resume::create([
    //         'file_name' => $file->getClientOriginalName(),
    //         'file_path' => $path,
    //     ]);

    //     // Extract text from file
    //     $resumeText = $this->extractText(Storage::path($path));

    //     // Get job details
    //     $job = JobDetails::findOrFail($request->job_id);

    //     // Prepare prompt for Gemini
    //     $prompt = "Compare this resume with the job description and give a match score out of 10, 
    //     and short feedback about skills matched and missing.\n\n
    //     Job Title: {$job->title}\n
    //     Job Description: {$job->description}\n\n
    //     Resume:\n{$resumeText}";

    //     // Call Gemini API
    //     $response = Http::withHeaders([
    //         'x-goog-api-key' => env('GEMINI_API_KEY'),
    //         'Content-Type' => 'application/json',
    //     ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent', [
    //         "contents" => [
    //             [
    //                 "parts" => [
    //                     ["text" => $prompt]
    //                 ]
    //             ]
    //         ],
    //         "generationConfig" => [
    //             "thinkingConfig" => ["thinkingBudget" => 0]
    //         ]
    //     ]);

    //     $data = $response->json();

    //     // Extract AI result
    //     $aiResult = $data['candidates'][0]['content']['parts'][0]['text'] ?? "No result returned from AI";

    //     // Extract numeric score (out of 10)
    //     preg_match('/(\d+(\.\d+)?)/', $aiResult, $match);
    //     $score = null;
    //     if (preg_match('/Match Score:\s*(\d+(\.\d+)?)\/10/i', $aiResult, $match)) {
    //         $score = floatval($match[1]);
    //     }

    //     // Store score and feedback
    //     ResumeScore::create([
    //         'job_id' => $job->id,
    //         'resume_id' => $resume->id,
    //         'score' => $score,
    //         'feedback' => $aiResult,
    //         'scanned_at' => now(),
    //     ]);

    //     //Return view with results
    //     return view('scan-result', [
    //         'job' => $job,
    //         'resume' => $resume,
    //         'aiResult' => $aiResult,
    //         'score' => $score,
    //     ]);
    // }

    public function storeResume(Request $request)
    {
        try {
            // Step 1: Validate Input
            $request->validate([
                'resume' => 'required|file|mimes:pdf,docx,txt|max:2048',
                'job_id' => 'required|exists:job_details,id',
            ]);

            // Step 2: Handle File Upload
            if (!$request->hasFile('resume')) {
                throw ValidationException::withMessages(['resume' => 'Please upload a valid resume file.']);
            }

            $file = $request->file('resume');
            if (!$file->isValid()) {
                throw ValidationException::withMessages(['resume' => 'Uploaded file is not valid.']);
            }

            $path = $file->store('resumes');
            if (!$path) {
                throw new Exception('Failed to store resume file.');
            }

            $resume = Resume::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]);

            // Step 3: Extract text from resume
            $resumeText = $this->extractText(Storage::path($path));
            if (empty(trim($resumeText))) {
                throw new Exception('Unable to extract text from the uploaded resume.');
            }

            // Step 4: Fetch Job Details
            $job = JobDetails::findOrFail($request->job_id);

            // Step 5: Prepare Gemini Prompt
            $prompt = "Compare this resume with the job description and give a match score out of 10, 
            and short feedback about skills matched and missing.\n\n
            Job Title: {$job->title}\n
            Job Description: {$job->description}\n\n
            Resume:\n{$resumeText}";

            // Step 6: Call Gemini API
            $apiKey = env('GEMINI_API_KEY');
            if (empty($apiKey)) {
                throw new Exception('Gemini API key is missing. Please configure it in your .env file.');
            }

            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(20)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent', [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ]
                ],
            ]);

            // Step 7: Handle API Errors
            if ($response->failed()) {
                $error = $response->json('error.message') ?? 'Failed to connect to Gemini API.';
                Log::error('Gemini API Error:', ['response' => $response->json()]);
                throw new Exception($error);
            }

            $data = $response->json();
            
            // Parse matched and missing skills from AI text
            $matchedSkills = [];
            $missingSkills = [];

            // Step 8: Parse AI Result
            $aiResult = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if (!$aiResult) {
                throw new Exception('No result returned from AI. Please try again later.');
            }

            // Extract skills listed after "Skills Matched"
            if (preg_match('/Skills Matched.*?:\s*(.*?)Skills Missing/is', $aiResult, $match)) {
                $skillsText = strip_tags($match[1]);
                preg_match_all('/\*\*([^*]+)\*\*/', $skillsText, $skillMatches);
                $matchedSkills = $skillMatches[1] ?? [];
            }
            
            // Extract skills listed after "Skills Missing"
            if (preg_match('/Skills Missing.*?:\s*(.*?)Overall/is', $aiResult, $match)) {
                $skillsText = strip_tags($match[1]);
                preg_match_all('/\*\*([^*]+)\*\*/', $skillsText, $skillMatches);
                $missingSkills = $skillMatches[1] ?? [];
            }

            // Step 9: Extract numeric score (out of 10)
            $score = null;
            if (preg_match('/Match Score:\s*(\d+(\.\d+)?)\/10/i', $aiResult, $match)) {
                $score = floatval($match[1]);
            } elseif (preg_match('/(\d+(\.\d+)?)/', $aiResult, $match)) {
                $score = floatval($match[1]);
            }

            // Step 10: Store Score & Feedback
            ResumeScore::create([
                'job_id' => $job->id,
                'resume_id' => $resume->id,
                'score' => $score,
                'feedback' => $aiResult,
                'scanned_at' => now(),
            ]);

            // Step 11: Return Success View
            return view('scan-result', [
                'job' => $job,
                'resume' => $resume,
                'aiResult' => $aiResult,
                'score' => $score,
                'matchedSkills' => $matchedSkills,
                'missingSkills' => $missingSkills,
            ]);

        } catch (ValidationException $e) {
            // Return back with validation errors
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Resume Scan Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // Return back with a user-friendly message
            return back()->with('error', 'Something went wrong while processing your resume: ' . $e->getMessage())->withInput();
        }
    }

    private function extractText($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $text = '';

        if ($extension === 'pdf') {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();
        } elseif ($extension === 'docx') {
            $phpWord = IOFactory::load($path);
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . " ";
                    }
                }
            }
        } elseif ($extension === 'txt') {
            $text = file_get_contents($path);
        }

        return $text;
    }
}