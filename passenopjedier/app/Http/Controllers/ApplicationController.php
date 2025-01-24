<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

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

    public function getMyApplications()
    {
        return Application::where('applicantid', Auth::id())
            ->join('vacancy', 'applications.vacancyid', '=', 'vacancy.vacancyid')
            ->join('users', 'vacancy.ownerid', '=', 'users.id')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->select(
                'applications.*',
                'pet.name as pet_name',
                'users.name as owner_name',
                'users.id as ownerid',
                'vacancy.rate',
                'vacancy.datetime'
            )
            ->orderBy('applications.applicationid', 'desc')
            ->get();
    }

    public function withdraw($applicationId)
    {
        $application = Application::where('applicationid', $applicationId)
            ->where('applicantid', Auth::id())
            ->firstOrFail();

        $application->delete();

        return redirect()->back()->with('success', 'Sollicitatie succesvol ingetrokken');
    }

    public function delete($applicationId)
    {
        $application = Application::where('applicationid', $applicationId)
            ->where('applicantid', Auth::id())
            ->firstOrFail();

        $application->delete();

        return redirect()->back()->with('success', 'Sollicitatie succesvol verwijderd');
    }
}
