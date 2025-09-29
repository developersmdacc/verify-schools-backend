<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    public function __construct()
    {
        // Only admin can create, update, delete
        $this->middleware('can:manage-schools')->except(['index', 'show']);
    }

    // List all schools
    // public function index()
    // {
    //     $schools = School::with(['country', 'province', 'city'])->get();
    //     return response()->json($schools);
    // }

    public function index(Request $request)
    {
        $query = $request->query('q', '');
        $type = $request->query('phase_ped', '');
        $province = $request->query('province', '');

        $schools = School::query()
            ->when($query, fn($q) => $q->where('official_institution_name', 'like', "%$query%"))
            ->get();

        return response()->json($schools);
    }

   public function show($id)
    {
        $school = School::findOrFail($id); // find by ID
        return response()->json($school);
    }

    // Create new school
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|unique:schools',
            'description' => 'nullable|string',
            'is_verified' => 'boolean',
            'country_id' => 'required|exists:countries,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'student_count' => 'nullable|integer',
            'teacher_count' => 'nullable|integer',
            'school_type' => 'nullable|string',
            'principal_name' => 'nullable|string',
        ]);

        $school = School::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'School created successfully',
            'school' => $school
        ]);
    }

    // Update existing school
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'registration_number' => 'sometimes|nullable|string|unique:schools,registration_number,' . $school->id,
            'description' => 'nullable|string',
            'is_verified' => 'boolean',
            'country_id' => 'sometimes|required|exists:countries,id',
            'province_id' => 'sometimes|required|exists:provinces,id',
            'city_id' => 'sometimes|required|exists:cities,id',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'student_count' => 'nullable|integer',
            'teacher_count' => 'nullable|integer',
            'school_type' => 'nullable|string',
            'principal_name' => 'nullable|string',
        ]);

        $school->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'School updated successfully',
            'school' => $school
        ]);
    }

    // Delete a school
    public function destroy(School $school)
    {
        $school->delete();

        return response()->json([
            'success' => true,
            'message' => 'School deleted successfully'
        ]);
    }
}
