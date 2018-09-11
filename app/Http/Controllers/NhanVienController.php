<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\nhan_vien;
use App\nhan_vien_lao_dong;
use App\nhan_vien_cong_viec_hien_tai;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;


class NhanVienController extends Controller
{
    public function get_all_thong_tin_nhan_vien()
    {
        $tt = nhan_vien::leftjoin('nhan_vien_lao_dong as ld', 'nhan_vien.id', '=', 'ld.nv_id')->paginate(10);
        return $tt;
    }

    public function get_all()
    {
        $nv = nhan_vien::orderby('id','desc')->paginate(10);
        return json_encode($nv);
    }

    public function add_nhan_vien_thong_tin_ca_nhan(Request $request) {
//        return $request->all();
        $avatar = '';
        if($request->get('image'))
        {
            $image = $request->get('image');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('/image_nhan_vien/').$name);
            $avatar = $name;
        }
        if($this->check_ma_nhan_vien($request->ma_nv) == 1) return 0;
        $nv = new nhan_vien();
        $nv->ma_nv = $request->ma_nv;
        $nv->ho_ten = $request->ho_ten;
        $nv->gioi_tinh = $request->gioi_tinh;
        $nv->ngay_sinh = $request->ngay_sinh;
        $nv->noi_sinh = $request->noi_sinh;
        $nv->quoc_tich = $request->quoc_tich;
        $nv->so_cmnd = $request->so_cmnd;
        $nv->scmnd_noi_cap = $request->scmnd_noi_cap;
        $nv->scmnd_ngay_cap = $request->scmnd_ngay_cap;
        $nv->thuong_tru = $request->thuong_tru;
        $nv->tam_tru = $request->tam_tru;
        $nv->di_dong_1 = $request->di_dong_1;
        $nv->di_dong_2 = $request->di_dong_2;
        $nv->dt_ban = $request->dt_ban;
        $nv->mst_cn = $request->mst_cn;
        $nv->mst_cn_noi_cap = $request->mst_cn_noi_cap;
        $nv->cc_thue_cap = $request->cc_thue_cap;
        $nv->avatar = $avatar;
        $nv->save();

        $nv_id = nhan_vien::max('id');
        $lao_dong = new nhan_vien_lao_dong();
        $lao_dong->nv_id = $nv_id;
        $lao_dong->save();

        $cong_viec = new nhan_vien_cong_viec_hien_tai();
        $cong_viec->nv_id = $nv_id;
        $cong_viec->save();

        return 1;
    }

    public function edit_nhan_vien_thong_tin_ca_nhan(Request $request) {
        $avatar = '';
        try{
            if($request->get('image') != null)
            {
                $image = $request->get('image');
                $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($request->get('image'))->save(public_path('/image_nhan_vien/').$name);
                $avatar = $name;
            }
        }catch (\Exception $exception){return $exception;}

        $nv = nhan_vien::find($request->id);
        $nv->ho_ten = $request->ho_ten;
        $nv->gioi_tinh = $request->gioi_tinh;
        $nv->ngay_sinh = $request->ngay_sinh;
        $nv->noi_sinh = $request->noi_sinh;
        $nv->quoc_tich = $request->quoc_tich;
        $nv->so_cmnd = $request->so_cmnd;
        $nv->scmnd_noi_cap = $request->scmnd_noi_cap;
        $nv->scmnd_ngay_cap = $request->scmnd_ngay_cap;
        $nv->thuong_tru = $request->thuong_tru;
        $nv->tam_tru = $request->tam_tru;
        $nv->di_dong_1 = $request->di_dong_1;
        $nv->di_dong_2 = $request->di_dong_2;
        $nv->dt_ban = $request->dt_ban;
        $nv->mst_cn = $request->mst_cn;
        $nv->mst_cn_noi_cap = $request->mst_cn_noi_cap;
        $nv->cc_thue_cap = $request->cc_thue_cap;
        ($avatar == '') ? $avatar = '' : $nv->avatar = $avatar;
        $nv->save();
        return 1;
    }

    public function check_ma_nhan_vien($ma_nv)
    {
        $nv = nhan_vien::where('ma_nv', $ma_nv)->get();
        (count($nv) == 0) ? $result = -1 : $result = 1;
        return $result;
    }

    public function add_nhan_vien_thong_tin_lao_dong(Request $request) {
        $nv1 = nhan_vien_lao_dong::where('nv_id',$request->nv_id)->get();
        if(count($nv1) > 0){
            $result = $this->edit_nhan_vien_thong_tin_lao_dong($request);
            return $result;
        }
        else{
            $nv= new nhan_vien_lao_dong();
            $nv->nv_id = $request->nv_id;
            $nv->vao_cty = $request->vao_cty;
            $nv->thoi_viec = $request->thoi_viec;
            $nv->nam = $request->nam;
            $nv->thang = $request->ngay;
            $nv->ngay = $request->vao_cty;
            $nv->so_hdld = $request->so_hdld;
            $nv->ngay_ky = $request->ngay_ky;
            $nv->thoi_han = $request->thoi_han;
            $nv->het_han = $request->het_han;
            $nv->so_bhxh = $request->so_bhxh;
            $nv->so_bhyt = $request->so_bhyt;
            $nv->noi_kham = $request->noi_kham;
            $nv->dia_chi_kham = $request->dia_chi_kham;
            $nv->ghi_chu = $request->ghi_chu;
            $nv->save();
            return 1;
        }

    }

    public function edit_nhan_vien_thong_tin_lao_dong(Request $request) {
        $nv = nhan_vien_lao_dong::where('nv_id', $request->nv_id);
        $nv->update([
            'vao_cty' => $request->vao_cty,
            'thoi_viec' => $request->thoi_viec,
            'nam' => $request->nam,
            'thang' => $request->ngay,
            'ngay' => $request->vao_cty,
            'so_hdld' => $request->so_hdld,
            'ngay_ky' => $request->ngay_ky,
            'thoi_han' => $request->thoi_han,
            'het_han' => $request->het_han,
            'so_bhxh' => $request->so_bhxh,
            'so_bhyt' => $request->so_bhyt,
            'noi_kham' => $request->noi_kham,
            'dia_chi_kham' => $request->dia_chi_kham,
            'ghi_chu' => $request->ghi_chu
        ]);
        return 1;
    }

    public function get_thong_tin_lao_dong_theo_nhan_vien($nv_id)
    {
        $nv = nhan_vien_lao_dong::where('nv_id',$nv_id)->first();
        return json_encode($nv);
    }

    public function get_thong_tin_cong_viec_theo_nhan_vien($nv_id)
    {
        $cv = nhan_vien_cong_viec_hien_tai::where('nv_id', $nv_id)->paginate(10);
        return $cv;
    }

    public function add_nhan_vien_thong_tin_cong_viec(Request $request)
    {
        $cv = nhan_vien_cong_viec_hien_tai::where('nv_id', $request->nv_id);
        $cv->id_elpv            = $request->id_elpv;
        $cv->nv_id              = $request->nv_id;
        $cv->id_phong           = $request->id_phong;
        $cv->id_vi_tri          = $request->id_vi_tri;
        $cv->he_so              = $request->he_so;
        $cv->luong_co_ban       = $request->luong_co_ban;
        $cv->luong_htcv         = $request->luong_htcv;
        $cv->thoi_gian_lam_viec = $request->thoi_gian_lam_viec;
        $cv->tinh_trang         = $request->tinh_trang;
        $cv->cham_cong          = $request->cham_cong;
        $cv->ghi_chu            = $request->ghi_chu;
        return 1;
    }

    public function edit_nhan_vien_thong_tin_cong_viec(Request $request)
    {
        $cv = nhan_vien_cong_viec_hien_tai::where('nv_id', $request->nv_id);
        $cv->update([
            'id_elpv'            => $request->id_elpv,
            'id_phong'           => $request->id_phong,
            'id_vi_tri'          => $request->id_vi_tri,
            'he_so'              => $request->he_so,
            'luong_co_ban'       => $request->luong_co_ban,
            'luong_htcv'         => $request->luong_htcv,
            'thoi_gian_lam_viec' => $request->thoi_gian_lam_viec,
            'tinh_trang'         => $request->tinh_trang,
            'cham_cong'          => $request->cham_cong,
            'ghi_chu'            => $request->ghi_chu
        ]);
        return 1;
    }

    public function upload_image(Request $request)
    {
        return $request->all();
        if($request->get('image'))
        {
            $image = $request->get('image');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('/image_nhan_vien/').$name);
            $nv = nhan_vien::where('ma_nv',$request->ma_nv)->update(['avatar' => $name]);
            return 1;
        }
        else{
            return -1;
        }

    }

    public function search_thong_tin_nhan_vien(Request $request)
    {
        $nv = nhan_vien::where('ho_ten', 'LIKE', "%{$request->keyword}%")
                ->orwhere('ma_nv', 'like', "%{$request->keyword}%")
                ->orwhere('so_cmnd', 'like', "%{$request->keyword}%")
                ->orwhere('di_dong_1', 'like', "%{$request->keyword}%")
                ->orwhere('di_dong_1', 'like', "%{$request->keyword}%")
                ->orwhere('di_dong_2', 'like', "%{$request->keyword}%")
                ->paginate(10);
        return $nv;
    }

    public function delete_all_thong_tin_nhan_vien($id_nv)
    {
        $lao_dong = nhan_vien_lao_dong::where('nv_id', $id_nv)->delete();

        $cong_viec = nhan_vien_cong_viec_hien_tai::where('nv_id', $id_nv)->delete();

        $nv = nhan_vien::find($id_nv);
        $nv->delete();

        return 1;
    }
}
