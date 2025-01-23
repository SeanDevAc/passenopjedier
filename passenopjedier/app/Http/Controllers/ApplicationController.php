<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function accept($id)
    {
        $application = DB::table('applications')->where('applicationid', $id)->first();

        // Update vacancy met sitterid
        DB::table('vacancy')
            ->where('vacancyid', $application->vacancyid)
            ->update(['sitterid' => $application->applicantid]);

        // Verwijder application
        DB::table('applications')->where('applicationid', $id)->delete();

        return redirect()->back()->with('success', 'Sollicitatie geaccepteerd');
    }

    public function reject($id)
    {
        // Verwijder alleen de application
        DB::table('applications')->where('applicationid', $id)->delete();

        return redirect()->back()->with('success', 'Sollicitatie geweigerd');
    }
}
