<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\Pengaturan;
use App\Models\Pinjaman;
use App\Models\PinjamanAngsuran;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public function kirimNotifikasiWhatsapp($phone, $message)
    {

        try {
            $curl = curl_init();
            $token = env('WA_TOKEN');
            curl_setopt($curl, CURLOPT_URL, "https://jogja.wablas.com/api/send-message?phone=$phone&message=$message&token=$token");
            $result = curl_exec($curl);
            curl_close($curl);
            echo "<pre>";
            print_r($result);
        } catch (\Throwable $th) {
            // throw $th;
            Log("Error kirim whatsapp ke  081919956872");
        }
    }

    public function admin_submitPinjaman($pinjaman_id)
    {
        $pinjaman = Pinjaman::with('anggota')->find($pinjaman_id);
        $data_admin = User::whereIn('role', ['admin', 'super admin'])->get();

        // looping admin
        foreach ($data_admin as $admin) {
            $message = "";
            $message .= 'Kepada yang Terhormat ' . $admin->name . ' <br> <br>';
            $message .= "Kami ingin memberitahu Anda bahwa ada sebuah pengajuan pinjaman baru yang telah masuk ke sistem. Berikut adalah detailnya: <br><br>";
            $message .= "- Kode Pinjaman : " . $pinjaman->kode . "<br>";
            $message .= "- Nama Peminjam : " . $pinjaman->anggota->nama . "<br>";
            $message .= "- Jumlah Pinjaman : " . formatRupiah($pinjaman->besar_pinjaman) . "<br>";
            $message .= "- Keperluan : " . $pinjaman->keperluan . "<br>";
            $message .= "- Tanggal Pengajuan : " . formatTanggalBulanTahun($pinjaman->created_at) . "<br><br>";
            $message .= "Silakan masuk ke sistem admin untuk melakukan peninjauan dan verifikasi lebih lanjut terhadap pengajuan ini sesuai dengan kebijakan dan prosedur yang berlaku.";
            $message .= "Terima kasih atas perhatian Anda terhadap pengajuan ini.";
            $message .= "<br><br><br>";
            $message .= "By " . $this->setting()->nama_situs;

            $this->kirimNotifikasiWhatsapp($admin->phone_number, $message);
        }
    }

    public function admin_bukti_pembayaran_angsuran($pinjaman_angsuran_id)
    {
        $pinjaman_angsuran = PinjamanAngsuran::with(['pinjaman.anggota', 'metode_pembayaran'])->findOrFail($pinjaman_angsuran_id);
        $pinjaman = $pinjaman_angsuran->pinjaman;
        $data_admin = User::whereIn('role', ['admin', 'super admin'])->get();

        // looping admin
        foreach ($data_admin as $admin) {
            $message = "";
            $message .= 'Halo ' . $admin->name . ', <br> <br>';
            $message .= "Kami ingin memberitahu Anda bahwa seorang anggota baru saja mengunggah bukti pembayaran angsuran untuk pinjaman mereka. Berikut ini detailnya: <br><br>";
            $message .= "- Kode Pinjaman : " . $pinjaman->kode . "<br>";
            $message .= "- Bulan/Tahun Angsuran : " . konversiBulan($pinjaman_angsuran->bulan) . ' ' . $pinjaman_angsuran->tahun . "<br>";
            $message .= "- Nominal : " . formatRupiah($pinjaman->total_jumlah_angsuran_bulan) . "<br>";
            $message .= "- Metode Pembayaran : " . $pinjaman_angsuran->metode_pembayaran->getFull() . "<br>";
            $message .= "- Tanggal Upload : " . formatTanggalBulanTahun(Carbon::now()) . "<br><br>";
            $message .= "Silahkan cek sesuai dengan metode pembayarannya, jika sudah sesuai maka segera verifikasi.";
            $message .= "Terima kasih atas perhatiannya, dan kami menghargai kerja sama Anda dalam menjalankan operasi keuangan kami.";
            $message .= "<br><br><br>";
            $message .= "Salam, <br>" . $this->setting()->nama_situs;

            $this->kirimNotifikasiWhatsapp($admin->phone_number, $message);
        }
    }

    public function anggota_pinjaman_disetujui($pinjaman_id)
    {
        $pinjaman = Pinjaman::with('anggota')->find($pinjaman_id);
        $is_jk = $pinjaman->anggota->jenis_kelamin === 'Laki-laki' ? 'Bapak' : 'Ibu';
        $message = "";
        $message .= 'Kepada yang Terhormat ' . $is_jk . ' ' . $pinjaman->anggota->nama . ', <br> <br>';
        $message .= "Kami ingin memberitahu Anda bahwa permohonan pinjaman Anda telah disetujui oleh tim kami. Kami senang bisa membantu Anda memenuhi kebutuhan keuangan Anda. : <br><br>";
        $message .= "- Kode Pinjaman : " . $pinjaman->kode . "<br>";
        $message .= "- Nama Peminjam : " . $pinjaman->anggota->nama . "<br>";
        $message .= "- Jumlah Pinjaman : " . formatRupiah($pinjaman->besar_pinjaman) . "<br>";
        $message .= "- Keperluan : " . $pinjaman->keperluan . "<br>";
        $message .= "- Tanggal Pengajuan : " . formatTanggalBulanTahun($pinjaman->created_at) . "<br><br>";
        $message .= "Terima kasih telah memilih " . $this->setting()->nama_situs . " sebagai mitra keuangan Anda. Kami berharap pinjaman ini dapat membantu Anda mencapai tujuan keuangan Anda.";
        $message .= "<br><br><br>";
        $message .= "Salam, <br>"  . $this->setting()->nama_situs;

        $this->kirimNotifikasiWhatsapp($pinjaman->anggota->nomor_telepon, $message);
    }

    public function anggota_pinjaman_ditolak($pinjaman_id)
    {
        $pinjaman = Pinjaman::with('anggota')->find($pinjaman_id);
        $is_jk = $pinjaman->anggota->jenis_kelamin === 'Laki-laki' ? 'Bapak' : 'Ibu';
        $message = "";
        $message .= 'Kepada yang Terhormat ' . $is_jk . ' ' . $pinjaman->anggota->nama . ', <br> <br>';
        $message .= "Kami ingin memberitahu Anda bahwa permohonan pinjaman Anda telah kami tinjau, namun dengan sangat menyesal kami harus memberitahukan bahwa permohonan pinjaman Anda telah ditolak pada saat ini. Berikut detail pinjamannya : <br><br>";
        $message .= "- Kode Pinjaman : " . $pinjaman->kode . "<br>";
        $message .= "- Nama Peminjam : " . $pinjaman->anggota->nama . "<br>";
        $message .= "- Jumlah Pinjaman : " . formatRupiah($pinjaman->besar_pinjaman) . "<br>";
        $message .= "- Keperluan : " . $pinjaman->keperluan . "<br>";
        $message .= "- Tanggal Pengajuan : " . formatTanggalBulanTahun($pinjaman->created_at) . "<br><br>";
        $message .= "Kami memahami betapa pentingnya kebutuhan keuangan Anda, tetapi sayangnya dalam situasi ini kami tidak dapat memberikan persetujuan. Kami menyarankan Anda untuk mempertimbangkan opsi lain atau menghubungi kami untuk informasi lebih lanjut tentang bagaimana Anda dapat meningkatkan peluang Anda untuk mendapatkan persetujuan di masa mendatang. <br>";
        $message .= "Terima kasih telah memilih " . $this->setting()->nama_situs . ". Kami harap Anda tetap sukses dalam perjalanan keuangan Anda..";
        $message .= "<br><br><br>";
        $message .= "Salam, <br>"  . $this->setting()->nama_situs;

        $this->kirimNotifikasiWhatsapp($pinjaman->anggota->nomor_telepon, $message);
    }

    public function anggota_verifikasi_angsuran_pinjaman($pinjaman_angsuran_id)
    {
        $pinjaman_angsuran = PinjamanAngsuran::with(['pinjaman.anggota', 'metode_pembayaran'])->findOrFail($pinjaman_angsuran_id);
        $pinjaman = $pinjaman_angsuran->pinjaman;

        $is_jk = $pinjaman->anggota->jenis_kelamin === 'Laki-laki' ? 'Bapak' : 'Ibu';
        $message = "";
        $message .= 'Kepada yang Terhormat ' . $is_jk . ' ' . $pinjaman->anggota->nama . ', <br> <br>';
        $message .= "Kami ingin memberitahu Anda bahwa bukti pembayaran angsuran untuk pinjaman Anda telah berhasil diverifikasi oleh tim kami. Terima kasih atas pembayaran tepat waktu! : <br><br>";
        $message .= "Detail Pembayaran : " . $pinjaman->kode . "<br>";
        $message .= "- Kode Pinjaman : " . $pinjaman->kode . "<br>";
        $message .= "- Bulan/Tahun Angsuran : " . konversiBulan($pinjaman_angsuran->bulan) . ' ' . $pinjaman_angsuran->tahun . "<br>";
        $message .= "- Nominal : " . formatRupiah($pinjaman->total_jumlah_angsuran_bulan) . "<br>";
        $message .= "- Metode Pembayaran : " . $pinjaman_angsuran->metode_pembayaran->getFull() . "<br>";
        $message .= "- Tanggal Verifikasi : " . formatTanggalBulanTahun(Carbon::now()) . "<br><br>";
        $message .= "Terima kasih atas kerja sama Anda dalam menjaga keteraturan pembayaran pinjaman Anda. Semoga Anda terus berhasil mencapai tujuan keuangan Anda.";
        $message .= "<br><br><br>";
        $message .= "Salam, <br>"  . $this->setting()->nama_situs;

        $this->kirimNotifikasiWhatsapp($pinjaman->anggota->nomor_telepon, $message);
    }

    public function anggota_pinjaman_selesai($pinjaman_id)
    {
        $pinjaman = Pinjaman::with(['anggota', 'angsuran'])->findOrFail($pinjaman_id);

        $is_jk = $pinjaman->anggota->jenis_kelamin === 'Laki-laki' ? 'Bapak' : 'Ibu';
        $message = "";
        $message .= 'Kepada yang Terhormat ' . $is_jk . ' ' . $pinjaman->anggota->nama . ', <br> <br>';
        $message .= "Kami sangat senang untuk memberitahu Anda bahwa pinjaman Anda telah berhasil dilunasi sepenuhnya. Selamat atas pencapaian ini!. <br><br>";
        $message .= "Detail Pinjaman : " . $pinjaman->kode . "<br>";
        $message .= "- Kode Pinjaman : " . $pinjaman->kode . "<br>";
        $message .= "- Besar Pinjaman : " . formatRupiah($pinjaman->total_jumlah_angsuran_bulan) . "<br>";
        $message .= "- Jumlah Angsuran : " . $pinjaman->angsuran()->count() . " bulan<br>";
        $message .= "- Tanggal Pelunasan : " . formatTanggalBulanTahun(Carbon::now()) . "<br><br>";
        $message .= "Anda telah melakukan pembayaran dengan konsisten, dan sekarang pinjaman Anda telah dilunasi. Ini adalah pencapaian besar, dan kami berharap Anda merasa bangga atas hal ini. <br>Semoga keberhasilan ini membantu Anda mencapai tujuan keuangan Anda. Kami berharap dapat terus melayani Anda di masa mendatang.";
        $message .= "<br><br><br>";
        $message .= "Salam, <br>"  . $this->setting()->nama_situs;

        $this->kirimNotifikasiWhatsapp($pinjaman->anggota->nomor_telepon, $message);
    }

    public function setting()
    {
        $pengaturan = Pengaturan::first();
        return $pengaturan;
    }
}
