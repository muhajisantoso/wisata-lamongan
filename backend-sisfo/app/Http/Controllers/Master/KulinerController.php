<?php

namespace App\Http\Controllers\Master;

use Log;
use Storage;
use App\Http\Controllers\Controller;
use App\Models\Kuliner;
use App\Http\Requests\StoreKulinerRequest;
use App\Http\Requests\UpdateKulinerRequest;

class KulinerController extends Controller
{
    public function json()
    {
        $data = Kuliner::with(['kategori'])->get();

        return $this->sendResponse('Ok', $data, 200);
    }

    public function store(StoreKulinerRequest $request)
    {
        try {
            $validated = $request->validated();

            $request->file('gambar')->store('public');

            $name = $request->gambar->hashName();

            $validated['gambar'] = $name;

            $data = Kuliner::create($validated);

            Log::info(json_encode($data) . ' created');

            return $this->sendResponse(__('response.store.success', ['attribute' => 'kuliner']), $data, 200);
        } catch (\Throwable $th) {
            $errors = [];
            if (config('app.debug')) {
                $errors = $th->getTraceAsString();
            }

            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $errors);
        }
    }

    public function show(Kuliner $kuliner)
    {
        return $this->sendResponse('found', $kuliner);
    }

    public function update(UpdateKulinerRequest $request, Kuliner $kuliner)
    {
        try {
            $validated = $request->validated();

            Storage::delete('public/gambar/' . $kuliner->gambar);

            $request->file('gambar')->store('public');

            $name = $request->gambar->hashName();

            $validated['gambar'] = $name;

            Kuliner::find($kuliner->id)
                ->update($validated);

            $data = $kuliner->refresh();

            Log::info(json_encode($data) . ' updated');

            return $this->sendResponse(__('response.update.success', ['attribute' => 'kuliner']), $data, 200);
        } catch (\Throwable $th) {
            $errors  = [];
            if (config('app.debug')) {
                $errors = $th->getTraceAsString();
            }
            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $th->getTraceAsString(), $errors);
        }
    }

    public function destroy(Kuliner $kuliner)
    {
        if (Kuliner::find($kuliner) == null) {
            return $this->sendResponse(__('response.delete.error', ['attribute' => 'ruang']), [], 404);
        }

        Kuliner::destroy($kuliner->id);

        return $this->sendResponse(__('response.delete.success', ['attribute' => 'ruang']), [], 204);
    }
}
