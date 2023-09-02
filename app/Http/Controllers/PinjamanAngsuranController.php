<?php

namespace App\Http\Controllers;

use App\Models\PinjamanAngsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinjamanAngsuranController extends Controller
{
    public function update($id)
    {
        request()->validate([
            'status' => ['required', 'numeric']
        ]);


        DB::beginTransaction();
        try {
            $status = request('status');
            $item = PinjamanAngsuran::findOrFail($id);
            $item->update([
                'status' => $status
            ]);
            DB::commit();

            return redirect()->back()->with('success', 'Status Angsuran berhasil diupdate.');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Status Angsuran gagal diupdate.');
        }
    }
}
