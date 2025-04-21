<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function index() {
        return Year::all();
    }

    public function store(Request $request) {
        $year = Year::create([
            'year' => $request->input('year')
        ]);
        return response()->json(['message' => 'Ročník bol úspešne vytvorený.'], 200);
    }

    public function destroy(Year $year) {
        $year->delete();
        return response()->json(['message' => 'Ročník bol odstranený.'], 200);
    }
}
