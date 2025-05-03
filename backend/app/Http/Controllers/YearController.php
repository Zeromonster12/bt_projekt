<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException; // Import this class

class YearController extends Controller
{
    public function index() {
        return Year::all();
    }

    public function store(Request $request) {
        try {
            $year = Year::create([
                'year' => $request->input('year'),
                'user_id' => $request->input('user_id'),
            ]);
            return response()->json(['message' => 'Ročník bol úspešne vytvorený.'], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // SQLSTATE[23000] is for integrity constraint violations
                return response()->json(['message' => 'Rok už existuje.'], 400);
            }
            return response()->json(['message' => 'Nastala chyba pri vytváraní ročníka.'], 500);
        }
    }

    public function destroy(Year $id) {
        $id->delete();
        return response()->json(['message' => 'Ročník bol odstranený.'], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $year = Year::findOrFail($id);
            $year->update($request->all());
            return response()->json(['message' => 'Ročník bol úspešne aktualizovaný.', 'year' => $year], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // SQLSTATE[23000] je pre porušenie jedinečnosti
                return response()->json(['message' => 'Rok už existuje.'], 400);
            }
            return response()->json(['message' => 'Nastala chyba pri aktualizácii ročníka.'], 500);
        }
    }
}