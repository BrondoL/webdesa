<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
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
            return response()->json($data, 404);
        } else {
            $data = [
                'success' => true,
                'message' => 'List ID akun',
                'data' => Auth::all('id', 'username', 'name'),
            ];
            return response()->json($data, 200);
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
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:auth,username',
            'email' => 'required|unique:auth,email|email:rfc,dns',
            'password' => 'min:8|required_with:password_confirmation|confirmed',
        ], [
            'name.required' => 'Masukkan username !',
            'username.required' => 'Masukkan username !',
            'username.unique' => 'Username sudah ada !',
            'email.required' => 'Masukkan email !',
            'email.unique' => 'Email sudah ada !',
            'email.email' => 'Email salah !',
            'password.min' => 'Password minimal 8 karakter !',
            'password.confirmed' => 'Password tidak cocok !'
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
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password'), [
                    'rounds' => 12,
                ]),
                'role_id' => 1,
            ];
            $auth = Auth::create($simpan);

            if ($auth) {
                $data = [
                    'success' => true,
                    'message' => 'Akun berhasil disimpan',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Akun gagal disimpan',
                ];
                return response()->json($data, 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auth  $auth
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Auth $auth)
    {
        if ($request->input('api-key') != env('API_KEY')) {
            $data = [
                'success' => false,
                'message' => 'Akses denied !',
            ];
            return response()->json($data, 404);
        } else {
            $data = [
                'success' => true,
                'message' => 'Detail Akun',
                'data' => $auth,
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auth  $auth
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
            'password' => 'min:8|required_with:password_confirmation|confirmed',
        ], [
            'password.min' => 'Password minimal 8 karakter !',
            'password.confirmed' => 'Password tidak cocok !'
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
                'password' => $request->input('password'),
            ];
            $auth = Auth::find($id)->update($update);

            if ($auth) {
                $data = [
                    'success' => true,
                    'message' => 'Akun berhasil diupdate',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Akun gagal diupdate',
                ];
                return response()->json($data, 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auth  $auth
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

        $auth = Auth::find($id);

        if ($auth) {
            $auth->delete();
            $data = [
                'success' => true,
                'message' => 'Akun berhasil dihapus',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'success' => false,
                'message' => 'Akun gagal dihapus',
            ];
            return response()->json($data, 404);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Masukkan username !',
                'password.required' => 'Masukkan password !',
            ]
        );

        if ($validator->fails()) {
            $data = [
                'success' => false,
                'message' => 'Silahkan isi form dengan benar',
                'data' => $validator->errors(),
            ];
            return response()->json($data, 200);
        } else {
            $username = $request->input('username');
            $password = $request->input('password');

            $row = Auth::firstWhere('username', $username);
            if (!$row) {
                $data = [
                    'success' => false,
                    'message' => 'Username tidak ditemukan!',
                    'data' => ['username' => "Username tidak ditemukan!"],
                ];
                return response()->json($data, 200);
            } else {
                if (!Hash::check($password, $row->password)) {
                    $data = [
                        'success' => false,
                        'message' => 'Password salah!',
                        'data' => ['password' => "Password salah!"],
                    ];
                    return response()->json($data, 200);
                } else {
                    $data = [
                        'success' => true,
                        'message' => 'Anda berhasil login',
                        'data' => [
                            "nama" => $row->name,
                            "email" => $row->email,
                            "username" => $row->username,
                            "role_id" => $row->role_id,
                        ],
                    ];
                    return response()->json($data, 200);
                }
            }
        }
    }
}
