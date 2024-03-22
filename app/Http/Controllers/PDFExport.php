<?php

namespace App\Http\Controllers;

use App\Models\Notify;
use Illuminate\Http\Request;

class PDFExport extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index($messsage = '')
    {
        //Notification
        $notify = Notify::notify();
        $data['notify'] = Notify::$notify($messsage);
        $data['trial'] =  false;
        $data['data'] = (object) [
            'base_url' => url('/media'),
        ];

        return view('pdf.guide', $data);
    }
}
