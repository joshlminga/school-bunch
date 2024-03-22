<?php

namespace App\Http\Controllers;

use App\Models\Notify;
use Psr\Log\NullLogger;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageFileController extends Controller
{
    private $Upload = "school"; //Upload Folder Name inside the public/admin/media
    private $AllowedFile = "xls,xlsx,csv"; //Set Default allowed file extension, remember you can pass this upon upload to override default allowed file type. jpg|jpeg|png|doc|docx|

    /**
     * Display the user's profile form.
     */
    public function index($messsage = ''): View
    {
        //Notification
        $notify = Notify::notify();
        $data['notify'] = Notify::$notify($messsage);

        return view('file.upload', $data);
    }

    /**
     * Display the user's profile form.
     */
    public function myfiles($messsage = ''): View
    {
        //Notification
        $notify = Notify::notify();
        $data['notify'] = Notify::$notify($messsage);

        // ? Get all files
        $data['mydocuments'] = \App\Models\SchoolDoc::all();

        return view('file.all', $data);
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
}
