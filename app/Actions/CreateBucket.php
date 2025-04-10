<?php

namespace App\Actions;

use App\Models\Bucket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateBucket
{
    public function __invoke(Request $request): RedirectResponse
    {
        $fields = $request->validate([
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Bucket::create([
            'created_by' => $request->user()->id,
            'project_id' => $fields['project_id'],
            'name' => $fields['name'],
        ]);

        return response()->redirectTo($request->header('X-From'));
    }
}
