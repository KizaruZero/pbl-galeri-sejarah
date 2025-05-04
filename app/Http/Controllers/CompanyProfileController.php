<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyProfile;

class CompanyProfileController extends Controller
{
    //
    public function index()
    {
        $companyProfile = CompanyProfile::first();
        return response()->json([
            'data' => $companyProfile,
            'message' => 'Company Profile retrieved successfully'
        ]);
    }
}
