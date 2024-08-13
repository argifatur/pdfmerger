<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Spatie\PdfToText\Pdf;

class PdfController extends Controller
{
    public function upload()
    {
        return view('backend.create');
    }
    
    public function merge()
    {
        return view('backend.merge');
    }
    
    public function pdfToText()
    {
        // $text = (new Pdf("C:\Program Files\Git\mingw64\bin\pdftotext.exe"))
        // ->setPdf('testing.pdf')
        // ->text();
        // return $text;
        return view('backend.pdf-to-text');
    }
    
    public function prosesPdfToText(Request $request)
    {
        $this->validate($request, [
            'pdf' => 'required',
            'pdf.*' => 'mimes:pdf',
        ]);
        $file = $request->file('pdf');
        $extention = $file->getClientOriginalExtension();
        $oriName = $file->getClientOriginalName();
        $modName = str_replace(" ","-",$oriName);
        // $filename = $modName .'.'.$extention;
        $filename = $modName;
        $uploadPdf = $file->storeAs('public/pdf_to_text', $filename);
        $text = (new Pdf("C:\Program Files\Git\mingw64\bin\pdftotext.exe"))
        // ->setPdf('testing.pdf')
        ->setPdf(storage_path('/app/public/pdf_to_text/'. $filename))
        ->text();
        return $text;
        return back()->with('message', 'Berhasil Konversi');
    }
    
    public function filepond()
    {
        return view('backend.filepond');
    }
    
    public function uploadMerge()
    {
        return view('backend.uploadmerge');
    }
    
    public function prosesMerge(Request $request)
    {
        $this->validate($request, [
            'pdf' => 'required',
            'pdf.*' => 'mimes:pdf',
        ]);

        if ($request->hasFile('pdf')) {
            $pdf = PDFMerger::init();

            foreach ($request->file('pdf') as $key => $value) {
                $pdf->addPDF($value->getPathName(), 'all');
            }

            $fileName = time() . '.pdf';
            $pdf->merge();
            $pdf->save(public_path($fileName));
        }

        $filePath = url($fileName); // Generate URL for the merged PDF

        // Return a view with the preview link
        return view('pdf.preview', ['filePath' => $filePath]);
    }

    
    public function prosesMergeFilepond(Request $request)
    {
        $request->validate([
            'files.*' => 'required|string', // since files are sent as base64 strings
        ]);

        $filePaths = [];
        if ($request->has('files')) {
            foreach ($request->input('files') as $file) {
                $fileData = base64_decode($file); // decode the base64 string
                $fileName = uniqid() . '.pdf';
                $filePath = 'uploads/' . $fileName;
                Storage::put($filePath, $fileData); // store the file
                $filePaths[] = $filePath;
            }
        }

        if (empty($filePaths)) {
            return back()->with('error', 'No files to merge.');
        }

        $pdfMerger = PDFMerger::init();

        foreach ($filePaths as $filePath) {
            $pdfMerger->addPDF(storage_path('app/' . $filePath), 'all');
        }

        $outputPath = storage_path('app/uploads/merged.pdf');
        $pdfMerger->merge('file', $outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }
    
    public function prosesUpload(Request $request)
    {
        if($request->hasFile('file')){

            $uploadPath = "uploads/gallery/";
    
            $file = $request->file('file');
    
            $extention = $file->getClientOriginalExtension();
            $filename = time().'-'.rand(0,99).'.'.$extention;
            $file->move($uploadPath, $filename);
    
            $finalImageName = $uploadPath.$filename;
    
            Gallery::create([
                'image' => $finalImageName
            ]);
    
            return response()->json(['success' => 'Image Uploaded Successfully']);
        }
        else
        {
            return response()->json(['error' => 'File upload failed.']);
        }
    }
}
