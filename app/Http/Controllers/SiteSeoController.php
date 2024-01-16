<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\SiteSeo\IndexSiteSeoRequest;
use App\Models\SiteSeo;
use App\Http\Requests\SiteSeo\StoreSiteSeoRequest;
use App\Http\Requests\SiteSeo\UpdateSiteSeoRequest;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteSeoController extends Controller
{

    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new SiteSeo)
        );
    }

    public function store(StoreSiteSeoRequest $request)
    {
        $data = SiteSeo::create($request->validated());

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    public function show(Request $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new SiteSeo, $id)
        ]);
    }

    public function update(UpdateSiteSeoRequest $request, int $id)
    {
        $data = SiteSeo::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->validated()
        );

        return new JsonResponse([
            'data' => Filter::one($request, new SiteSeo, $id)
        ]);
    }

    
    public function destroy(int $id)
    {
        $data = SiteSeo::findOrFail($id);

        if (AccessUtil::cannot('delete', $data)) return AccessUtil::errorMessage();

        SiteSeo::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }

    public function restore(int $id)
    {
        $data = SiteSeo::onlyTrashed()->findOrFail($id);

        if (AccessUtil::cannot('restore', $data)) return AccessUtil::errorMessage();

        $data->restore();

        return new JsonResponse([
            'message' => 'Restored'
        ]);
    }

    public function forceDelete(int $id)
    {
        $data = SiteSeo::findOrFail($id);

        if (AccessUtil::cannot('forceDelete', $data)) return AccessUtil::errorMessage();

        $data->forceDelete();

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
