<?php

namespace App\Actions;

use App\Http\Resources\BucketResource;
use App\Models\Bucket;
use Illuminate\Http\Request;

class UpdateBucket
{
    public function __invoke(Request $request, int $id): BucketResource
    {
        $bucket = Bucket::findOrFail($id);

        $fields = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $bucket->update([
            'name' => $fields['name'],
        ]);

        return new BucketResource($bucket);
    }
}
