<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Notifikasi;
use App\Models\Penarikansaldo;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\UpdateGuruRequest;
use App\Models\Materi;
use App\Models\DetailMateri;
use App\Models\Order;
use App\Models\Pendapatan;
use App\Models\User;
use Illuminate\Http\Request;



class GuruController extends Controller
{
    public function Dashboardguru()
    {
        // $user = Auth::id();
        $Notifikasi = Notifikasi::where('user_id', Auth::user()->id)->whereNotIn('title', [Auth::user()->name])->orderBy('created_at', 'desc')->get();
        $unreadNotificationsCount = Notifikasi::where('user_id', Auth::user()->id)->whereNotIn('title', [Auth::user()->name])->where('markRead', false)->count();
        $guru = Guru::where('user_id', auth()->user()->id)->firstOrFail();
        $jumlahmateri = guru::count();
        $guru = Guru::with('user')->get();
        $pendapatanguru = Pendapatan::all()->where('user_id', auth()->id());
        $pendapatan = $pendapatanguru->pluck('pendapatan')->sum();
        $jumlahtransaksi = $pendapatanguru->count();

        return view('guru.dashboardguru', compact('guru', 'jumlahmateri', 'pendapatan', 'jumlahtransaksi', 'Notifikasi', 'unreadNotificationsCount'));
    }

    public function materi()
    {
        $Notifikasi = Notifikasi::where('user_id', Auth::user()->id)->whereNotIn('title', [Auth::user()->name])->orderBy('created_at', 'desc')->get();
        $unreadNotificationsCount = Notifikasi::where('user_id', Auth::user()->id)->whereNotIn('title', [Auth::user()->name])->where('markRead', false)->count();

        $guru = Guru::where('user_id', auth()->user()->id)->firstOrFail();
        // $guru = Guru::with('user')->get();
        return view('guru.materi', compact('guru', 'Notifikasi', 'unreadNotificationsCount'));
    }
    /**
     * Display a listing of the resource.
     */

    public function Pengumpulantugas(Request $request)
    {
        $Notifikasi = Notifikasi::where('user_id', Auth::user()->id)->whereNotIn('title', [Auth::user()->name])->orderBy('created_at', 'desc')->get();
        $unreadNotificationsCount = Notifikasi::where('user_id', Auth::user()->id)->whereNotIn('title', [Auth::user()->name])->where('markRead', false)->count();
        $guru = Guru::where('user_id', auth()->user()->id)->firstOrFail();
        return view('guru.pengumpulan', compact('guru', 'Notifikasi', 'unreadNotificationsCount'));
    }

    public function Penarikansaldo(Request $request)
    {
        $Notifikasi = Notifikasi::where('user_id', Auth::user()->id)->whereNotIn('title', [Auth::user()->name])->orderBy('created_at', 'desc')->get();
        $unreadNotificationsCount = Notifikasi::where('user_id', Auth::user()->id)->whereNotIn('title', [Auth::user()->name])->where('markRead', false)->count();


        $mengajukan = new Penarikansaldo;
        $mengajukan->metodepembayaran = $request->metodepembayaran;
        $mengajukan->keterangan_pengajuan = $request->keterangan_pengajuan;
        $mengajukan->tujuan_pengajuan = $request->tujuan_pengajuan;
        $mengajukan->status = 'sedang mengajukan';

        $mengajukan->save();
        return view('guru.pengajuansaldo', compact('Notifikasi', 'unreadNotificationsCount'));
    }

    public function mengajukandana(Request $request, $id)
    {
        // Temukan data berdasarkan ID
        $penarikansaldo = Penarikansaldo::findOrFail($id);

        // Ubah status menjadi "Telah diajukan"
        $penarikansaldo->guru_id = $request->guru_id;
        $penarikansaldo->status = 'Telah diajukan';
        $penarikansaldo->save();

        return redirect()->route('pengajuanguru')->with('success', 'Berhasil mengajukan dana!');
    }

    // public function profileGuru (){
    //     $user_id = Auth::id();
    //     $profileGuru = guru::all();
    //     return view ('guru.profileguru', compact ('user_id'));
    // }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()->route('loginPage')->with('success', 'berhasil logout');
    }


      public function materidetail(Request $request)
    {
      return view('guru.materidetail');
    }
}
