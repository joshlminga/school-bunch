<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Notify;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Validator;

class ManageFileController extends Controller
{
    private $Upload = "school"; //Upload Folder Name inside the public/admin/media
    private $AllowedFile = "xls,xlsx,csv"; //Set Default allowed file extension, remember you can pass this upon upload to override default allowed file type. jpg|jpeg|png|doc|docx|
    private $Trial = false;

    /**
     * Display the user's profile form.
     */
    public function index($message = ''): View
    {
        //Notification
        $notify = Notify::notify();
        $data['notify'] = Notify::$notify($message);
        $data['trial'] = ($this->Trial) ? 'Project in Trial Mode, Max 2 Doc, Export Max 4 PDF' : false;

        return view('file.upload', $data);
    }

    /**
     * Display the user's profile form.
     */
    public function myfiles($message = ''): View
    {
        //Notification
        $notify = Notify::notify();
        $data['notify'] = Notify::$notify($message);
        $data['trial'] = ($this->Trial) ? 'Project in Trial Mode, Max 2 Doc, Export Max 4 PDF' : false;

        // ? Get all files
        $data['mydocuments'] = \App\Models\SchoolDoc::all();

        return view('file.all', $data);
    }

    /**
     * Display the student info .
     */
    public function studentlist(Request $request, $doc = null, $info = null, $message = '')
    {
        // ? Validate Form Data
        $validator = Validator::make($request->all(), [
            'doc' => 'nullable|numeric|exists:schooldoc,id',
            'info' => 'nullable|numeric|exists:studentinfo,id',
        ]);

        // ? On Validation Fail
        if ($validator->fails()) {

            session()->flash('notification', 'error');
            Notify::error('Please check the form for errors.');

            // ? Return Error
            return redirect()->back()->withErrors($validator)->withInput($request->input());
        }

        // ? If Doc is not null
        if (!is_null($request->doc) && !is_null($request->info)) {
            $student_info = \App\Models\StudentInfo::where('doc', $request->doc)->where('id', $request->info)->where('flag', 1);
        } elseif (!is_null($request->doc)) {
            // ? Get the student info (doc info
            $student_info = \App\Models\StudentInfo::where('doc', $request->doc)->where('flag', 1);
        } elseif (!is_null($request->info)) {
            $student_info = \App\Models\StudentInfo::where('id', $request->info)->where('flag', 1);
        } else {
            $student_info = \App\Models\StudentInfo::where('flag', 1);
        }

        // ? Run the query
        $found = $student_info->paginate(15);

        //Notification
        $notify = Notify::notify();
        $data['notify'] = Notify::$notify($message);
        $data['trial'] = ($this->Trial) ? 'Project in Trial Mode, Max 2 Doc, Export Max 4 PDF' : false;
        $data['results'] = $found;
        $data['this_doc'] = (!is_null($doc)) ? $doc : '';

        return view('file.student', $data);
    }

    /**
     * Store files
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadfile(Request $request)
    {

        $allowed_files = (is_null($this->AllowedFile)) ? 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx,csv' : $this->AllowedFile; //Set Allowed Files
        $upoadDirectory = $this->Upload; //Upload Location

        // ? Validate Form Data
        $validator = Validator::make($request->all(), [
            'school_file' => "required|array",
            'school_file.*' => 'file|mimes:' . $allowed_files . '|max:1024',
        ], [
            'school_file.*.mimes' => "Only : $allowed_files files are allowed.",
        ]);

        // ? On Validation Failphp
        if ($validator->fails()) {

            session()->flash('notification', 'error');
            Notify::error('Please check the form for errors.');

            // ? Return Error
            return redirect()->back()->withErrors($validator)->withInput($request->input());
        }

        // ? Get Filename from 'school_file' input array
        $file_name = "";
        foreach ($request->file('school_file') as $file) {
            $file_name = $file->getClientOriginalName();
        }

        // ? Store File in public folder
        $files = \App\Models\FileUpload::upload($request->file('school_file'), $upoadDirectory, randomize_file_name: true, year_folder: true);
        // ? Uploaded Docs
        $spread_sheet_doc = $files;

        // ? Get File Name from the $spread_sheet_doc | nb xxxxx/xxxxx/xxxxx/xxxxx.xlsx
        $spread_sheet = explode("/", $spread_sheet_doc);
        $spread_sheet_file = end($spread_sheet);

        // ? Count all files
        $count_files = \App\Models\SchoolDoc::count();

        // ? Check if the file is more than 2
        if ($count_files >= 2 && $this->Trial) {

            // ? Return Error
            $message = "You have reached the maximum number of documents. Please upgrade to upload more documents.";
            session()->flash('notification', 'error');

            return $this->index($message);
        }

        // ? Save the file name to the database
        $school_doc = new \App\Models\SchoolDoc();
        $school_doc->name = $file_name;
        $school_doc->slug = $spread_sheet_file;
        $school_doc->path = $spread_sheet_doc;
        $school_doc->save();

        // ? Read the file
        $document_data = \App\Models\SchoolDoc::read(null, $spread_sheet_doc);

        // ? Check
        if (count($document_data) == 0) {

            // ? Delete
            $school_doc->delete();

            $message = "Document had an error, check if all columns are present and match the template.";
            session()->flash('notification', 'error');

            // ? Return Error
            return $this->index($message);
        }

        session()->flash('notification', 'success');
        $message = "The Document was uploaded successfully. Use the 'My Documents' to view the document.";

        // ? Return Success
        return $this->index($message);
    }

    /**
     * thisfile
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function thisfile(Request $request)
    {
        // ? Validate Form Data
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:schooldoc,id',
            'action' => 'required|string|max:10',
        ]);

        // ? On Validation Fail
        if ($validator->fails()) {

            session()->flash('notification', 'error');
            Notify::error('Please check the form for errors.');

            // ? Return Error
            return redirect()->back()->withErrors($validator)->withInput($request->input());
        }

        // ? Check Action
        if ($request->action == "delete") {
            // ? Delete the file
            if (!$this->destroy($request->id)) {
                $message = "Document could not be found / Upgrade Unlimited version.";
                session()->flash('notification', 'error');

                // ? Return Error
                return $this->myfiles($message);
            } else {
                $message = "Document was deleted successfully.";
                session()->flash('notification', 'success');

                // ? Return Error
                return $this->myfiles($message);
            }
        } elseif ($request->action == "import") {
            // ? Get the file
            $school_doc = \App\Models\SchoolDoc::find($request->id);
            // ? Check if the file exists
            if (!$school_doc) {

                $message = "Document could not be found.";
                session()->flash('notification', 'error');

                // ? Return Error
                return $this->myfiles($message);
            }

            // ? Get the file path
            $spread_sheet_doc = $school_doc->path;
            $document_data = \App\Models\SchoolDoc::read(null, $spread_sheet_doc, readonly: false);

            // ? Total Records StudentInfo where doc = $school_doc->id
            $student_school_doc = \App\Models\StudentInfo::where('doc', $school_doc->id)->get();
            $total_records = count($student_school_doc);

            // ? Save the data to the database
            foreach ($document_data as $this_id => $row) {

                // ? Save the data
                $student_info = new \App\Models\StudentInfo();
                $student_info->doc = $school_doc->id;

                $student_info->county = $row['name-of-county'];
                $student_info->subcounty = $row['sub-counties'];

                $student_info->assessor = json_encode($row['name-of-assessor']);
                $student_info->school = json_encode($row['name-of-school']);

                $student_info->electricity = $row['electricity'];
                $student_info->internet = $row['internet'];
                $student_info->ict = $row['ict-teacher'];
                $student_info->learner = $row['learners-name'];
                $student_info->assessment = $row['assessment-number'];
                $student_info->birth = $row['year-of-birth'];
                $student_info->gender = $row['gender'];
                $student_info->parent = $row['name-of-parent-guardian'];
                $student_info->phonenumber = $row['parent-guardian-phone-number'];
                $student_info->visual = json_encode($row['visual-ability']);
                $student_info->reading = json_encode($row['reading-ability']);
                $student_info->physical = json_encode($row['physical-ability']);
                $student_info->meta = json_encode($row);

                $student_info->printed = 0;
                $student_info->last_printed = null;

                // ? if trial
                if ($this->Trial && $this_id >= 5) {
                    $message = "Limit reached. Upgrade to upload more than 5 records.";
                    session()->flash('notification', 'error');
                    break;
                }

                // ? Save the data
                $student_info->save();
            }

            // ? Update mark as imported
            $school_doc->imported = 1;
            $school_doc->save();

            // ? Motification
            if (!isset($message)) {
                $message = "Document was imported successfully. Use the 'View Students' to view the students.";
                session()->flash('notification', 'success');
            }

            // ? Return Error
            return $this->myfiles($message);
        } else {
            // Todo: View Students
        }
    }

    /**
     * Todo: export
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // ? Validate Form Data
        $validator = Validator::make($request->all(), [
            'doc' => 'nullable|numeric|exists:schooldoc,id',
            'std' => 'nullable|numeric|exists:studentinfo,id',
        ]);

        // ? On Validation Fail
        if ($validator->fails()) {

            session()->flash('notification', 'error');
            Notify::error('Please check the form for errors.');

            // ? Return Error
            return redirect()->back()->withErrors($validator)->withInput($request->input());
        }

        // ? Count all $student_info where ->where('printed', 1)
        $printedCount = \App\Models\StudentInfo::where('printed', 1)->count();

        // ? If Doc is not null
        if (!is_null($request->doc) && !is_null($request->std)) {
            $student_info = \App\Models\StudentInfo::where('doc', $request->doc)->where('id', $request->std)->where('flag', 1);
        } elseif (!is_null($request->doc)) {
            // ? Get the student info (doc info
            $student_info = \App\Models\StudentInfo::where('doc', $request->doc)->where('flag', 1);
        } elseif (!is_null($request->std)) {
            $student_info = \App\Models\StudentInfo::where('id', $request->std)->where('flag', 1);
        } else {
            $student_info = \App\Models\StudentInfo::where('flag', 1);
        }

        $import_status = ($this->Trial) ? (($printedCount >= 5) ? false : true) : true;
        // ? Check if the file is more than 2
        if ($import_status) {

            // ? Where Printed = 0
            $student_info = $student_info->where('printed', 0)->orderBy('id', 'asc')->limit(60)->get();

            // ? loop
            $to_pdf_data = [];
            foreach ($student_info as $student) {
                // ? Update the printed
                $student->printed = 1;
                $student->last_printed = now();
                $student->save();

                // ? Get the data
                $to_pdf_data[] = [
                    "field" => $student->id,
                    "name-of-county" => $student->county,
                    "sub-counties" => $student->subcounty,
                    "name-of-assessor" => json_decode($student->assessor, true),
                    "name-of-school" => json_decode($student->school, true),
                    "electricity" => $student->electricity,
                    "internet" => $student->internet,
                    "ict-teacher" => $student->ict,
                    "learners-name" => $student->learner,
                    "assessment-number" => $student->assessment,
                    "year-of-birth" => $student->birth,
                    "gender" => $student->gender,
                    "name-of-parent-guardian" => $student->parent,
                    "parent-guardian-phone-number" => $student->phonenumber,
                    "visual-ability" => json_decode($student->visual, true),
                    "reading-ability" => json_decode($student->reading, true),
                    "physical-ability" => json_decode($student->physical, true),
                ];
            }

            // Array to hold the generated PDFs
            $pdfs = [];
            // ? Check files
            if (count($to_pdf_data) > 1) {
                // Loop through the array
                foreach ($to_pdf_data as $data) {

                    // PDF Directory
                    $pdf_file_name = $data['field'] . "_export_" . now()->format('Y-m-d_H-i-s') . ".pdf";
                    $db_pdf = 'app/public/media-private/pdf/' . $pdf_file_name;
                    $saved_dir = storage_path($db_pdf);

                    // Generate PDF
                    $pdf = PDF::loadView("pdf.print", compact('data'));
                    $pdf->setOption(['orientation' => 'portrait', 'enable-javascript' => true, 'enable-external-links' => true, 'enable-local-file-access' => true, 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
                    // return $pdf->download($pdf_file_name);
                    // Save the PDF to a temporary file
                    $pdf->save($saved_dir);

                    // Add the PDF to the array
                    $pdfs[] = $saved_dir;
                }

                // Create a new zip archive
                $zip = new ZipArchive;

                // The name of the zip file
                $zipFileName = now()->format('Y-m-d_H-i-s') . '-pdfs.zip';

                // Create the zip file
                if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
                    // Add files to the zip file
                    foreach ($pdfs as $pdf) {
                        $zip->addFile($pdf, basename($pdf));
                    }

                    // Close the zip file
                    $zip->close();
                }

                // Download the zip file
                return response()->download(public_path($zipFileName));
            } else {

                if (count($to_pdf_data) == 1) {

                    // ? Load Data
                    $data = $to_pdf_data[0];

                    $pdf_file_name = $data['field'] . "_export_" . now()->format('Y-m-d_H-i-s') . ".pdf";
                    $db_pdf = 'app/public/media-private/pdf/' . $pdf_file_name;

                    // Generate PDF
                    $pdf = PDF::loadView("pdf.print", compact('data'));
                    $pdf->setOption(['orientation' => 'portrait', 'enable-javascript' => true, 'enable-external-links' => true, 'enable-local-file-access' => true, 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
                    return $pdf->download($pdf_file_name);
                }
            }
        }

        // ? Return Error
        $message = "You have reached the maximum number of documents. Please upgrade to export more documents.";
        session()->flash('notification', 'error');

        // ? Return Error
        return $this->studentlist($request, message: $message, doc: $request->doc, info: $request->std);
    }

    /**
     * Todo: File Delete
     *
     * @param int fileId
     */
    private function destroy($fileId)
    {

        // ? Select the file
        $school_doc = \App\Models\SchoolDoc::find($fileId);
        // ? Check if the file exists
        if (!$school_doc) {
            return false;
        }

        // ? If Trial
        if ($this->Trial) {
            // ? Check if the file is more than 2
            return false;
        }

        // ? Delete the file
        $school_doc->delete();

        //? Return Success
        return true;
    }
}
