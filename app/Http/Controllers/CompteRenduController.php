<?php

namespace App\Http\Controllers;

use App\Models\CompteRendu;
use App\Models\Reunion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompteRenduController extends Controller
{
    public function store(Request $request, Reunion $reunion)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $path = $request->file('file')->store('compte_rendus');

        CompteRendu::updateOrCreate(
            ['reunion_id' => $reunion->id],
            [
                'file_path' => $path,
                'file_name' => $request->file('file')->getClientOriginalName(),
                'uploaded_by' => Auth::id(),
            ]
        );

        return back()->with('success', 'Compte rendu mis en ligne.');
    }

    public function download(CompteRendu $compteRendu)
    {
        return Storage::download($compteRendu->file_path, $compteRendu->file_name);
    }
}
