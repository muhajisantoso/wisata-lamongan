<?php

namespace App\Http\Controllers\Master;

use Log;
use App\Http\Controllers\Controller;
use App\Models\Acara;
use App\Http\Requests\StoreAcaraRequest;
use App\Http\Requests\UpdateAcaraRequest;
use DataTables;

class AcaraController extends Controller
{
    public function dt()
    {
        $data = Acara::query();

        return DataTables::of($data)->make(true);
    }

    public function store(StoreAcaraRequest $request)
    {
        try {
            $validated = $request->validated();

            $data = Acara::create($validated);

            Log::info(json_encode($data) . ' created');

            return $this->sendResponse(__('response.store.success', ['attribute' => 'acara']), $data, 200);
        } catch (\Throwable $th) {
            $errors = [];
            if (config('app.debug')) {
                $errors = $th->getTraceAsString();
            }

            Log::error($errors);

            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $errors);
        }
    }

    public function show(Acara $acara)
    {
        return $this->sendResponse('found', $acara);
    }

    public function update(UpdateAcaraRequest $request, Acara $acara)
    {
        try {
            $validated = $request->validated();

            Acara::find($acara->id)
                ->update($validated);

            $data = $acara->refresh();

            Log::info(json_encode($data) . ' updated');

            return $this->sendResponse(__('response.update.success', ['attribute' => 'acara']), $data, 200);
        } catch (\Throwable $th) {
            $errors  = [];
            if (config('app.debug')) {
                $errors = $th->getTraceAsString();
            }
            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $th->getTraceAsString(), $errors);
        }
    }

    public function destroy($acara)
    {
        if (Acara::find($acara) == null) {
            return $this->sendResponse(__('response.delete.error', ['attribute' => 'ruang']), [], 404);
        }

        Acara::destroy($acara);

        return $this->sendResponse(__('response.delete.success', ['attribute' => 'ruang']), [], 204);
    }
}
