<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('api-key') != env('API_KEY')) {
            $data = [
                'success' => false,
                'message' => 'Akses denied !',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'success' => true,
                'message' => 'List Semua Warga',
                'data' => DB::table('warga')->leftJoin('dusun', 'warga.dusun_id', '=', 'dusun.dusun_id')->orderBy('warga.created_at', 'desc')->get(),
            ];
            return response($data, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->input('api-key') != env('API_KEY')) {
            $data = [
                'success' => false,
                'message' => 'Akses denied !',
            ];
            return response()->json($data, 200);
        } else {
            $validator = Validator::make($request->all(), [
                'nama_warga' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'pekerjaan' => 'required',
                'agama' => 'required',
                'no_ktp' => 'required|unique:warga,no_ktp',
                'no_kk' => 'required',
            ], [
                'nama_warga.required' => 'Masukkan nama anda !',
                'tempat_lahir.required' => 'Masukkan tempat lahir !',
                'tanggal_lahir.required' => 'Masukkan tanggal lahir !',
                'pekerjaan.required' => 'Masukkan pekerjaan !',
                'agama.required' => 'Masukkan agama !',
                'no_ktp.required' => 'Masukkan nomor ktp !',
                'no_ktp.unique' => 'Nomor ktp sudah ada !',
                'no_kk.required' => 'Masukkan nomor kk !',
            ]);

            if ($validator->fails()) {
                $data = [
                    'success' => false,
                    'message' => 'Silahkan isi form dengan benar',
                    'data' => $validator->errors(),
                ];
                return response()->json($data, 200);
            } else {
                $simpan = [
                    'nama_warga' => $request->input('nama_warga'),
                    'dusun_id' => $request->input('dusun_id'),
                    'jenis_kelamin' => $request->input('jenis_kelamin'),
                    'tempat_lahir' => $request->input('tempat_lahir'),
                    'tanggal_lahir' => $request->input('tanggal_lahir'),
                    'pekerjaan' => $request->input('pekerjaan'),
                    'pendidikan' => $request->input('pendidikan'),
                    'agama' => $request->input('agama'),
                    'status_perkawinan' => $request->input('status_perkawinan'),
                    'no_ktp' => $request->input('no_ktp'),
                    'no_kk' => $request->input('no_kk'),
                ];
                $warga = Warga::create($simpan);

                if ($warga) {
                    $data = [
                        'success' => true,
                        'message' => 'Data warga berhasil disimpan',
                    ];
                    return response()->json($data, 200);
                } else {
                    $data = [
                        'success' => false,
                        'message' => 'Data warga gagal disimpan',
                    ];
                    return response()->json($data, 200);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->input('api-key') != env('API_KEY')) {
            $data = [
                'success' => false,
                'message' => 'Akses denied !',
            ];
            return response()->json($data, 200);
        } else {
            $warga = DB::table('warga')->leftJoin('dusun', 'warga.dusun_id', '=', 'dusun.dusun_id')->where('warga_id', $id)->first();
            if ($warga) {
                $data = [
                    'success' => true,
                    'message' => 'Detail warga',
                    'data' => $warga
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Warga tidak ditemukan',
                ];
                return response()->json($data, 200);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warga $warga)
    {
        if ($request->input('api-key') != env('API_KEY')) {
            $data = [
                'success' => false,
                'message' => 'Akses denied !',
            ];
            return response()->json($data, 200);
        }

        $validator = Validator::make($request->all(), [
            'nama_warga' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'pekerjaan' => 'required',
            'agama' => 'required',
            'no_ktp' => 'required|unique:warga,no_ktp,' . $warga->warga_id . ',warga_id',
            'no_kk' => 'required',
        ], [
            'nama_warga.required' => 'Masukkan nama anda !',
            'tempat_lahir.required' => 'Masukkan tempat lahir !',
            'tanggal_lahir.required' => 'Masukkan tanggal lahir !',
            'pekerjaan.required' => 'Masukkan pekerjaan !',
            'agama.required' => 'Masukkan agama !',
            'no_ktp.required' => 'Masukkan nomor ktp !',
            'no_ktp.unique' => 'Nomor ktp sudah ada !',
            'no_kk.required' => 'Masukkan nomor kk !',
        ]);

        if ($validator->fails()) {
            $data = [
                'success' => false,
                'message' => 'Silahkan isi form dengan benar',
                'data' => $validator->errors(),
            ];
            return response()->json($data, 200);
        } else {
            $update = [
                'nama_warga' => $request->input('nama_warga'),
                'dusun_id' => $request->input('dusun_id'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'tempat_lahir' => $request->input('tempat_lahir'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'pekerjaan' => $request->input('pekerjaan'),
                'pendidikan' => $request->input('pendidikan'),
                'agama' => $request->input('agama'),
                'status_perkawinan' => $request->input('status_perkawinan'),
                'no_ktp' => $request->input('no_ktp'),
                'no_kk' => $request->input('no_kk'),
            ];
            $warga = Warga::where('warga_id', $warga->warga_id)->update($update);

            if ($warga) {
                $data = [
                    'success' => true,
                    'message' => 'Warga berhasil diupdate',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Warga gagal diupdate',
                ];
                return response()->json($data, 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->input('api-key') != env('API_KEY')) {
            $data = [
                'success' => false,
                'message' => 'Akses denied !',
            ];
            return response()->json($data, 200);
        }
        $warga = Warga::find($id);

        if ($warga) {
            $warga->delete();
            $data = [
                'success' => true,
                'message' => 'Warga berhasil dihapus',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'success' => false,
                'message' => 'Warga gagal dihapus',
            ];
            return response()->json($data, 200);
        }
    }

    public function getWarga()
    {
        $data = [
            'success' => true,
            'message' => 'Total warga di desa Pandan Makmur',
            'data' => Warga::count(),
        ];
        return response()->json($data, 200);
    }
}
