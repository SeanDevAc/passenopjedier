<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function cancel($id)
    {
        DB::table('vacancy')
            ->where('vacancyid', $id)
            ->update(['sitterid' => null]);

        return redirect()->back()->with('success', 'Afspraak geannuleerd');
    }
}
