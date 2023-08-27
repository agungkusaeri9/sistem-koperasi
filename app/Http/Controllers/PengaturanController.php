<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $item = Pengaturan::first();
        return view('pages.pengaturan.index', [
            'item' => $item,
            'title' => 'Pengaturan'
        ]);
    }

    public function update()
    {
        request()->validate([
            'nama_situs' => ['required'],
            'favicon' => ['image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            'logo' => ['image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
        ]);
        $pengaturan = Pengaturan::first();

        DB::beginTransaction();

        try {
            $data = request()->except(['logo', 'favicon']);
            if ($pengaturan) {
                // cek favicon
                if (request()->file('favicon')) {
                    // cek favicon pengaturan
                    if ($pengaturan->favicon)
                        Storage::disk('public')->delete($pengaturan->favicon);
                    $data['favicon'] = request()->file('favicon')->store('pengaturan', 'public');
                }
                // cek logo
                if (request()->file('logo')) {
                    // cek logo pengaturan
                    if ($pengaturan->logo)
                        Storage::disk('public')->delete($pengaturan->logo);
                    $data['logo'] = request()->file('logo')->store('pengaturan', 'public');
                }
                $pengaturan->update($data);
            } else {
                // cek favicon
                if (request()->file('favicon')) {
                    $data['favicon'] = request()->file('favicon')->store('pengaturan', 'public');
                }
                // cek logo
                if (request()->file('logo')) {
                    $data['logo'] = request()->file('logo')->store('pengaturan', 'public');
                }
                Pengaturan::create($data);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Pengaturan berhasil diupdate.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
