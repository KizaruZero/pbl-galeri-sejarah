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
        ], 200);
    }

    public function getHomeSection()
    {
        $companyProfile = CompanyProfile::first();
        return response()->json([
            'data' => [
                'bg_home_1' => $companyProfile->bg_home_1,
                'bg_home_2' => $companyProfile->bg_home_2,
                'bg_home_3' => $companyProfile->bg_home_3,
                'cms_name' => $companyProfile->cms_name,
                'cms_email' => $companyProfile->cms_email,
                'cms_phone' => $companyProfile->cms_phone,
                'cms_address' => $companyProfile->cms_address,
            ]
        ], 200);
    }

    public function getEventsSection()
    {
        $companyProfile = CompanyProfile::first();
        return response()->json([
            'data' => [
                'bg_events_1' => $companyProfile->bg_events_1,
                'bg_events_2' => $companyProfile->bg_events_2,
                'bg_events_3' => $companyProfile->bg_events_3,
                'events_text' => $companyProfile->events_text,
                'cms_name' => $companyProfile->cms_name,
                'cms_email' => $companyProfile->cms_email,
                'cms_phone' => $companyProfile->cms_phone,
                'cms_address' => $companyProfile->cms_address,
            ]
        ], 200);
    }

    public function getGallerySection()
    {
        $companyProfile = CompanyProfile::first();
        return response()->json([
            'data' => [
                'bg_gallery_1' => $companyProfile->bg_gallery_1,
                'bg_gallery_2' => $companyProfile->bg_gallery_2,
                'bg_gallery_3' => $companyProfile->bg_gallery_3,
                'gallery_text' => $companyProfile->gallery_text,
                'cms_name' => $companyProfile->cms_name,
                'cms_email' => $companyProfile->cms_email,
                'cms_phone' => $companyProfile->cms_phone,
                'cms_address' => $companyProfile->cms_address,
            ]
        ], 200);
    }

    public function getArticleSection()
    {
        $companyProfile = CompanyProfile::first();
        return response()->json([
            'data' => [
                'bg_article_1' => $companyProfile->bg_article_1,
                'bg_article_2' => $companyProfile->bg_article_2,
                'bg_article_3' => $companyProfile->bg_article_3,
                'article_text' => $companyProfile->article_text,
                'cms_name' => $companyProfile->cms_name,
                'cms_email' => $companyProfile->cms_email,
                'cms_phone' => $companyProfile->cms_phone,
                'cms_address' => $companyProfile->cms_address,
            ]
        ], 200);
    }

    public function getMemberSection()
    {
        $companyProfile = CompanyProfile::first();
        return response()->json([
            'data' => [
                'bg_member_1' => $companyProfile->bg_member_1,
                'bg_member_2' => $companyProfile->bg_member_2,
                'bg_member_3' => $companyProfile->bg_member_3,
                'member_text' => $companyProfile->member_text,
                'cms_name' => $companyProfile->cms_name,
                'cms_email' => $companyProfile->cms_email,
                'cms_phone' => $companyProfile->cms_phone,
                'cms_address' => $companyProfile->cms_address,
            ]
        ], 200);
    }
}