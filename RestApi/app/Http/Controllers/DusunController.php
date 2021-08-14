<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dusun;
use Illuminate\Support\Facades\Validator;

class DusunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dusuns = Dusun::all();
        $data = [
            'success' => true,
            'message' => 'List Semua Dusun',
            'data' => $dusuns
        ];
        return response($data, 200);
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
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'nama_dusun' => 'required',
        ], [
            'nama_dusun.required' => 'Masukkan nama dusun !',
        ]);

        if ($validator->fails()) {
            $data = [
                'success' => false,
                'message' => 'Silahkan isi form dengan benar',
                'data' => $validator->errors(),
            ];
            return response()->json($data, 400);
        } else {
            $simpan = [
                'nama_dusun' => $request->input('nama_dusun'),
            ];
            $auth = Dusun::create($simpan);

            if ($auth) {
                $data = [
                    'success' => true,
                    'message' => 'Dusun berhasil disimpan',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Dusun gagal disimpan',
                ];
                return response()->json($data, 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dusun = Dusun::where('dusun_id', $id)->first();
        if ($dusun) {
            $data = [
                'success' => true,
                'message' => 'Detail dusun',
                'data' => $dusun
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'success' => false,
                'message' => 'Dusun tidak ditemukan',
                'data' => ''
            ];
            return response()->json($data, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->input('api-key') != env('API_KEY')) {
            $data = [
                'success' => false,
                'message' => 'Akses denied !',
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'nama_dusun' => 'required',
        ], [
            'nama_dusun.required' => 'Masukkan nama dusun !',
        ]);

        if ($validator->fails()) {
            $data = [
                'success' => false,
                'message' => 'Silahkan isi form dengan benar',
                'data' => $validator->errors(),
            ];
            return response()->json($data, 400);
        } else {
            $update = [
                'nama_dusun' => $request->input('nama_dusun'),
            ];
            $dusun = Dusun::where('dusun_id', $id)->update($update);

            if ($dusun) {
                $data = [
                    'success' => true,
                    'message' => 'Dusun berhasil diupdate',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Dusun gagal diupdate',
                ];
                return response()->json($data, 404);
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
            return response()->json($data, 404);
        }
        $dusun = Dusun::find($id);

        if ($dusun) {
            $dusun->delete();
            $data = [
                'success' => true,
                'message' => 'Dusun berhasil dihapus',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'success' => false,
                'message' => 'Dusun gagal dihapus',
            ];
            return response()->json($data, 404);
        }
    }

    public function getDusun()
    {
        $data = [
            'success' => true,
            'message' => 'Total dusun di desa Pandan Makmur',
            'data' => Dusun::count(),
        ];
        return response()->json($data, 200);
    }
}
