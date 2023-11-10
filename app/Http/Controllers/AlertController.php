<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Alert\StoreAlertRequest;
use App\Http\Requests\Alert\UpdateAlertRequest;
use App\Models\Alert;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    private $request_only = [
        'title',
        'description',
        'country_id',
        'role',
        'type',
    ];

    private static function getWhere()
    {
        return [];
    }

    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new Alert, [], $this::getWhere())
        );
    }

    public function store(StoreAlertRequest $request)
    {
        $data = Alert::create([
            ...$request->only($this->request_only),
        ]);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    public function show(Request $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new Alert, $id, $this::getWhere())
        ]);
    }


    public function update(UpdateAlertRequest $request, int $id)
    {
        $data = Alert::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only($this->request_only)
        );

        return new JsonResponse([
            'data' => Filter::one($request, new Alert, $id)
        ]);
    }

    public function destroy(int $id)
    {
        $partner = Alert::findOrFail($id);

        if (AccessUtil::cannot('delete', $partner)) return AccessUtil::errorMessage();

        Alert::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
