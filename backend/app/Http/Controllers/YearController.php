<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class YearController
{
    public function index() {
        return Year::all();
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'year' => 'required|integer|min:1900|max:2100|unique:years,year',
            ]);
            
            $year = Year::create([
                'year' => $validated['year'],
            ]);
            return response()->json(['message' => 'Ročník bol úspešne vytvorený.'], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['message' => 'Rok už existuje.'], 400);
            }
            return response()->json(['message' => 'Nastala chyba pri vytváraní ročníka.'], 500);
        }
    }

    public function destroy(Year $id) {
        try {
            $id->delete();
            return response()->json(['message' => 'Ročník bol odstranený.'], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['message' => 'Ročník obsahuje príspevky.'], 400);
            }
            return response()->json(['message' => 'Nastala chyba pri odstraňovaní ročníka.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'year' => 'required|integer|min:1900|max:2100|unique:years,year,'.$id.',id',
            ]);
            
            $year = Year::findOrFail($id);
            $year->update([
                'year' => $validated['year']
            ]);
            
            return response()->json(['message' => 'Ročník bol úspešne aktualizovaný.', 'year' => $year], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // SQLSTATE[23000] je pre porušenie jedinečnosti
                return response()->json(['message' => 'Rok už existuje.'], 400);
            }
            return response()->json(['message' => 'Nastala chyba pri aktualizácii ročníka.'], 500);
        }
    }
}