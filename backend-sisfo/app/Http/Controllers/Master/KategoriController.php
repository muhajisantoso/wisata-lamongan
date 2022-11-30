<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    public function json()
    {
        $data = Kategori::all();

        return $this->sendResponse('Ok', $data, 200);
    }

    public function store(StoreKategoriRequest $request)
    {
        try {
            $validated = $request->validated();

            $data = Kategori::create($validated);

            Log::info(json_encode($data) . ' created');

            return $this->sendResponse(__('response.store.success', ['attribute' => 'kategori']), $data, 200);
        } catch (\Throwable $th) {
            $errors = [];
            if (config('app.debug')) {
                $errors = $th->getTraceAsString();
            }

            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $errors);
        }
    }

    public function show(Kategori $kategori)
    {
        return $this->sendResponse('found', $kategori);
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        try {
            $validated = $request->validated();

            Kategori::find($kategori->id)
                ->update($validated);

            $data = $kategori->refresh();

            Log::info(json_encode($data) . ' updated');

            return $this->sendResponse(__('response.update.success', ['attribute' => 'kategori']), $data, 200);
        } catch (\Throwable $th) {
            $errors  = [];
            if (config('app.debug')) {
                $errors = $th->getTraceAsString();
            }
            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $th->getTraceAsString(), $errors);
        }
    }

    public function destroy($kategori)
    {
        if (Kategori::find($kategori) == null) {
            return $this->sendResponse(__('response.delete.error', ['attribute' => 'ruang']), [], 404);
        }

        Kategori::destroy($kategori);

        return $this->sendResponse(__('response.delete.success', ['attribute' => 'ruang']), [], 204);
    }
}
