<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Spatie\PdfToText\Pdf;
use PHPHtmlParser\Dom;


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

        $filePath = secure_url($fileName); // Generate URL for the merged PDF
        // return $filePath;

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

    public function htmlParser(Request $request)
    {
        $dom = new \DOMDocument();
        // $htmlContent = file_get_contents('https://sps-inaportnet.dephub.go.id/index.php/builtin/manage/spog/cetak/SPS.SPOG.IDSRI.2409.002728');
        $htmlContent = file_get_contents('https://sps-inaportnet.dephub.go.id/index.php/builtin/manage/spog/cetak/SPS.SPOG.IDSRI.2411.015677');
        @$dom->loadHTML($htmlContent);

        $xpath = new \DOMXPath($dom);

        // Ekstrak data menggunakan XPath
        $noSurat = $xpath->query("//div[contains(text(), 'No :')]/text()")->item(0)->textContent ?? '';
        $noSurat = trim(str_replace('No : ', '', $noSurat)); // Menghapus 'No : ' dari hasil

        $namaKapal = $xpath->query("//td[contains(text(), 'Nama Kapal')]/following-sibling::td")->item(0)->textContent ?? '';
        $jenisKapal = $xpath->query("//td[contains(text(), 'Jenis Kapal')]/following-sibling::td")->item(0)->textContent ?? '';
        $bendera = $xpath->query("//td[contains(text(), 'Bendera')]/following-sibling::td")->item(0)->textContent ?? '';
        $isiKotor = $xpath->query("//td[contains(text(), 'Isi Kotor')]/following-sibling::td")->item(0)->textContent ?? '';
        $nahkoda = $xpath->query("//td[contains(text(), 'Nakhoda')]/following-sibling::td")->item(0)->textContent ?? '';
        $agen = $xpath->query("//td[contains(text(), 'Milik / Agen')]/following-sibling::td")->item(0)->textContent ?? '';
        $bergerakDari = $xpath->query("//td[contains(text(), 'Untuk bergerak dari')]/following-sibling::td")->item(0)->textContent ?? '';
        $waktuGerak = $xpath->query("//td[contains(text(), 'Waktu Gerak')]/following-sibling::td")->item(0)->textContent ?? '';
        $keperluan = $xpath->query("//td[contains(text(), 'Keperluan')]/following-sibling::td")->item(0)->textContent ?? '';
        $namaPandu = $xpath->query("//td[contains(text(), 'Nama Pandu')]/following-sibling::td")->item(0)->textContent ?? '';

        return [
            'no_surat' => $noSurat,
            'nama_kapal' => str_replace(': ', '', trim($namaKapal)),
            'jenis_kapal' => str_replace(': ', '', trim($jenisKapal)),
            'bendera' => str_replace(': ', '', trim($bendera)),
            'isi_kotor' => str_replace(': ', '', trim($isiKotor)),
            'nahkoda' => str_replace(': ', '', trim($nahkoda)),
            'agen' => str_replace(': ', '', trim($agen)),
            'bergerak_dari' => str_replace(': ', '', trim($bergerakDari)),
            'waktu_gerak' => str_replace(': ', '', trim($waktuGerak)),
            'keperluan' => str_replace(': ', '', trim($keperluan)),
            'nama_pandu' => str_replace(': ', '', trim($namaPandu)),
        ];
    }

    public function pdfQr(){
        return view('backend.pdf-qr');
    }

    public function prosesPdfQr(Request $request){
        $validator = Validator::make($request->all(), [
            'pdf_file' => 'required|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $path = $request->file('pdf_file')->store('uploads', 'public');

        $parser = new Parser();
        $pdf = $parser->parseFile(storage_path('app/public/' . $path));

        $qrCodeReader = new QrCodeReader();

        foreach ($pdf->getPages() as $page) {
            $images = $page->getImages();

            foreach ($images as $image) {
                $imagePath = storage_path('app/public/temp/' . uniqid() . '.png');
                file_put_contents($imagePath, $image->getPngData());

                try {
                    $result = $qrCodeReader->readFromFile($imagePath);
                    if ($result) {
                        unlink($imagePath);

                        Storage::disk('public')->delete($path);

                        return response()->json(['qr_code_content' => $result->getText()]);
                    }
                } catch (\Exception $e) {
                    unlink($imagePath);
                }
            }
        }

        // Hapus file PDF yang diunggah jika QR code tidak ditemukan
        Storage::disk('public')->delete($path);

        // Kembalikan pesan error jika QR code tidak ditemukan
        return response()->json(['error' => 'QR code tidak ditemukan dalam file PDF'], 404);
    }

}
