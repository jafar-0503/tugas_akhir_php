<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\tbl_biodata;

class Biodata extends Controller
{
  public function store(Request $request){
    $this->validate($request, [
      'file' => 'required|max:3048'
    ]);

    //simpan data file yang diupload
    $file = $request->file('file');

    $nama_file = time()."_".$file->getClientOriginalName();

    $tujuan_upload = 'data_file';
    if($file ->move($tujuan_upload, $nama_file)){
      $data = tbl_biodata::create([
        'nama' => $request->nama,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        'hobi' => $request->hobi,
        'foto' => $nama_file

      ]);
      $res['message'] = 'Data Berhasil Di Push!!!';
      $res['values'] = $data;
      return response($res);
    }
  }

  public function getData(){
    $data = DB::table('tbl_biodata')->get();
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
    $data = DB::table('tbl_biodata')->where('id',$id)->get();
    foreach ($data as $biodata) {
      $image_path = 'http://localhost/APIREST/public' .$biodata->foto;
      if (file_exists(public_path('data_file/'.$biodata->foto))){
        @unlink(public_path('data_file/'.$biodata->foto));
        DB::table('tbl_biodata')->where('id', $id)->delete();
        $res['message'] = 'Data Berhasil Dihapus !!!';
        return response($res);
      }else{
        $res['message'] = 'Empty!!';
        return response($res);
      }
    }
  }

  public function getDetail($id){
    $data = DB::table('tbl_biodata')->where('id',$id)->get();
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
      $data = DB::table('tbl_biodata')->where('id',$request->id)->get();
      foreach ($data as $biodata) {
        //fungsi hapus file
      @unlink(public_path('data_file/'.$biodata->foto));
      //fungsi update data
      $ket = DB::table('tbl_biodata')->where('id',$request->id)->update([
              'nama' => $request->nama,
              'no_hp' => $request->no_hp,
              'alamat' => $request->alamat,
              'hobi' => $request->hobi,
              'foto' => $nama_file
            ]);
      }
      $res['message'] = 'Success!';
      $res['values'] = $data;
      return response($res);
    }else{
      $data = DB::table('tbl_biodata')->where('id',$request->id)->get();
      foreach ($data as $biodata) {
      //fungsi update data
      $ket = DB::table('tbl_biodata')->where('id',$request->id)->update([
              'nama' => $request->nama,
              'no_hp' => $request->no_hp,
              'alamat' => $request->alamat,
              'hobi' => $request->hobi,
            ]);
      $res['message'] = 'Success!';
      $res['values'] = $data;
      return response($res);
      }
    }
  }
}
