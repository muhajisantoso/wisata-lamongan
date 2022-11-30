<?php

namespace App\Http\Controllers\Master;

use Log;
use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Http\Requests\StoreDestinasiRequest;
use App\Http\Requests\UpdateDestinasiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinasiController extends Controller
{
    public function json()
    {
        $data = Destinasi::with(['kategori'])->get();

        return $this->sendResponse('Ok', $data, 200);
    }

    public function store(StoreDestinasiRequest $request)
    {
        try {
            $validated = $request->validated();

            $request->file('gambar')->store('public');

            $name = $request->gambar->hashName();

            $validated['gambar'] = $name;

            $data = Destinasi::create($validated);

            Log::info(json_encode($data) . ' created');

            return $this->sendResponse(__('response.store.success', ['attribute' => 'destinasi']), $data, 200);
        } catch (\Throwable $th) {
            $errors = [];
            if (config('app.debug')) {
                $errors = $th->getTraceAsString();
            }

            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $errors);
        }
    }

    public function show(Destinasi $destinasi)
    {
        return $this->sendResponse('found', $destinasi);
    }

    public function update(UpdateDestinasiRequest $request, Destinasi $destinasi)
    {
        try {
            $validated = $request->validated();

            Storage::delete('public/gambar/' . $destinasi->gambar);

            $request->file('gambar')->store('public');

            $name = $request->gambar->hashName();

            $validated['gambar'] = $name;

            Destinasi::find($destinasi->id)
                ->update($validated);

            $data = $destinasi->refresh();

            Log::info(json_encode($data) . ' updated');

            return $this->sendResponse(__('response.update.success', ['attribute' => 'destinasi']), $data, 200);
        } catch (\Throwable $th) {
            $errors  = [];
            if (config('app.debug')) {
                $errors = $th->getTraceAsString();
            }
            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $th->getTraceAsString(), $errors);
        }
    }

    public function destroy(Destinasi $destinasi)
    {
        if (Destinasi::find($destinasi) == null) {
            return $this->sendResponse(__('response.delete.error', ['attribute' => 'ruang']), [], 404);
        }

        Destinasi::destroy($destinasi->id);

        return $this->sendResponse(__('response.delete.success', ['attribute' => 'ruang']), [], 204);
    }
}
