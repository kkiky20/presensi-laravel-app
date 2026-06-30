<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FaceController extends Controller
{
    public function register()
    {
        return view('face.register');
    }

    public function save(Request $request)
    {
        try {
            $nik = Auth::guard('karyawan')->user()->nik;
            $descriptor = $request->descriptor;

            if (!$descriptor) {
                return response()->json(['status' => 'error', 'message' => 'Data wajah tidak terdeteksi.']);
            }

            // Save descriptor as JSON file
            $fileName = $nik . '.json';
            $filePath = 'face-descriptors/' . $fileName;
            Storage::put($filePath, $descriptor);

            // Update file_wajah column in karyawan table
            DB::table('karyawan')->where('nik', $nik)->update([
                'file_wajah' => $fileName
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data wajah berhasil didaftarkan!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function getDescriptor()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        if (!$karyawan || !$karyawan->file_wajah) {
            return response()->json(['status' => 'error', 'message' => 'Wajah belum didaftarkan.']);
        }

        $filePath = 'face-descriptors/' . $karyawan->file_wajah;
        if (!Storage::exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'File wajah tidak ditemukan.']);
        }

        $descriptor = Storage::get($filePath);
        return response()->json(['status' => 'success', 'descriptor' => json_decode($descriptor)]);
    }
}
