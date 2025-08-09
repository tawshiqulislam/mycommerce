<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LegalPageController extends Controller
{
    public function show($slug)
    {
        $page = LegalPage::where('slug', $slug)
            ->where('isOn', true)
            ->first();

        if (!$page) {
            return Inertia::render('ErrorPage', [
                'status' => 404,
            ]);
        }

        return Inertia::render('LegalPage', [
            'legalPage' => $page,
        ]);
    }

    public function edit($id)
    {
        $page = LegalPage::findOrFail($id);
        return ['page' => $page];
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slug' => 'required|string|unique:legal_pages,slug,' . $id,
            'title' => 'required|string',
            'content' => 'required|string',
            'isOn' => 'required|boolean',
        ]);

        $page = LegalPage::findOrFail($id);
        $page->update($request->only('slug', 'title', 'content', 'isOn'));

        return ['page' => $page, 'message' => 'Page updated successfully.'];
    }
}