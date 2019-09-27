<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\tbl_karyawan;

class Karyawan extends Controller
{
    public function store(Request $request){
      $this->validate($request, [
        'file' => 'required|max:2048'
      ]);

      //simpan data file yang diupload
      $file = $request->file('file');

      $nama_file = time()."_".$file->getClientOriginalName();

      $tujuan_upload = 'data_file';
      if($file ->move($tujuan_upload, $nama_file)){
        $data = tbl_karyawan::create([
          'nama' => $request->nama,
          'jabatan' => $request->jabatan,
          'umur' => $request->umur,
          'alamat' => $request->alamat,
          'foto' => $nama_file
        ]);
        $res['message'] = 'Data Berhasil Di Push!!!';
        $res['values'] = $data;
        return response($res);
      }
    }

    public function getData(){
      $data = DB::table('tbl_karyawan')->get();
      if(count($data) > 0){
        $res['message'] = 'Data Success!';
        $res['values'] = $data;
        return response($res);
      }else{
        $res['message'] = 'Data Empty!!';
        return response($res);
      }
    }

    public function hapus($id){
      $data = DB::table('tbl_karyawan')->where('id',$id)->get();
      foreach ($data as $karyawan) {
        $image_path = 'http://localhost/APIREST/public' .$karyawan->foto;
        if (file_exists(public_path('data_file/'.$karyawan->foto))){
          @unlink(public_path('data_file/'.$karyawan->foto));
          DB::table('tbl_karyawan')->where('id', $id)->delete();
          $res['message'] = 'Data Berhasil Dihapus !!!';
          return response($res);
        }else{
          $res['message'] = 'Empty!!';
          return response($res);
        }
      }
    }

    public function getDetail($id){
      $data = DB::table('tbl_karyawan')->where('id',$id)->get();
      if(count($data) > 0){
        $res['message'] = 'Success Broo!';
        $res['values'] = $data;
        return response($res);
      }else{
        $res['message'] = 'Empty!!';
        return response($res);
      }
    }

    public function update(Request $request){
      if(!empty($request->file)){
        $this->validate($request, [
          'file' => 'required|max:2048'
        ]);

        //simpan data file yang diupload
        $file = $request->file('file');

        $nama_file = time()."_".$file->getClientOriginalName();

        $tujuan_upload = 'data_file';
        $file->move($tujuan_upload,$nama_file);
        $data = DB::table('tbl_karyawan')->where('id',$request->id)->get();
        foreach ($data as $karyawan) {
          //fungsi hapus file
        @unlink(public_path('data_file/'.$karyawan->foto));
        //fungsi update data
        $ket = DB::table('tbl_karyawan')->where('id',$request->id)->update([
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'umur' => $request->umur,
                'alamat' => $request->alamat,
                'foto' => $nama_file
              ]);
        }
        $res['message'] = 'Success!';
        $res['values'] = $data;
        return response($res);
      }else{
        $data = DB::table('tbl_karyawan')->where('id',$request->id)->get();
        foreach ($data as $karyawan) {
        //fungsi update data
        $ket = DB::table('tbl_karyawan')->where('id',$request->id)->update([
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'umur' => $request->umur,
                'alamat' => $request->alamat
              ]);
        $res['message'] = 'Success!';
        $res['values'] = $data;
        return response($res);
        }
      }
    }
}
