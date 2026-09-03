<?php

namespace App\Support;

use App\Models\Country;
use App\Models\Degree;
use App\Models\Designation;
use App\Models\District;
use App\Models\Organization;
use App\Models\Subject;
use Illuminate\Support\Facades\Cache;

class LookupCache
{
    public static function districts()
    {
        return Cache::remember('lookups.districts.active', now()->addMinutes(15), function () {
            return District::where('is_active', true)->orderBy('name')->get();
        });
    }

    public static function organizations()
    {
        return Cache::remember('lookups.organizations.active', now()->addMinutes(15), function () {
            return Organization::where('is_active', true)->orderBy('name')->get();
        });
    }

    public static function designations()
    {
        return Cache::remember('lookups.designations.active', now()->addMinutes(15), function () {
            return Designation::where('is_active', true)->orderBy('name')->get();
        });
    }

    public static function degrees()
    {
        return Cache::remember('lookups.degrees.active', now()->addMinutes(15), function () {
            return Degree::where('is_active', true)->orderBy('id')->get();
        });
    }

    public static function subjects()
    {
        return Cache::remember('lookups.subjects.active', now()->addMinutes(15), function () {
            return Subject::where('is_active', true)->orderBy('name')->get();
        });
    }

    public static function countries()
    {
        return Cache::remember('lookups.countries.active', now()->addMinutes(15), function () {
            return Country::where('is_active', true)->orderBy('name')->get();
        });
    }
}
