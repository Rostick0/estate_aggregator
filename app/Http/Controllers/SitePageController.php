<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\SitePage\DestroySitePageRequest;
use App\Models\SitePage;
use App\Http\Requests\SitePage\StoreSitePageRequest;
use App\Http\Requests\SitePage\UpdateSitePageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SitePageController extends Controller
{
    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new SitePage)
        );
    }

    public function show(string $path)
    {
        return new JsonResponse([
            'data' => SitePage::firstWhere([
                'path' => $path,
            ])
        ]);
    }

    public function update(UpdateSitePageRequest $request, int $path)
    {
        SitePage::firstWhere([
            'path' => $path,
        ])->update($request->validated());

        return new JsonResponse([
            'data' => SitePage::firstWhere([
                'path' => $path,
            ])
        ]);
    }
}
