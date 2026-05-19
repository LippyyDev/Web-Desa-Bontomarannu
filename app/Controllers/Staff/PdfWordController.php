<?php

namespace App\Controllers\Staff;

use App\Controllers\ProtectedController;
use App\Models\LetterModel;
use App\Models\UserProfileModel;
use App\Models\PerangkatDesaModel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;

class PdfWordController extends ProtectedController
{
    /**
     * Helper function untuk format tempat/tanggal lahir dengan pesan default
     */
    private function formatTempatTanggalLahir($profile, $bulan)
    {
        $tanggalLahir = '';
        if (!empty($profile['tanggal_lahir'])) {
            $tglLahir = date_create($profile['tanggal_lahir']);
            $tanggalLahir = date('d', $tglLahir->getTimestamp()) . ' ' . $bulan[(int)date('m', $tglLahir->getTimestamp()) - 1] . ' ' . date('Y', $tglLahir->getTimestamp());
        }
        
        $tempatLahir = !empty($profile['tempat_lahir']) ? $profile['tempat_lahir'] : '';
        
        // Jika keduanya string kosong eksplisit (untuk template), return kosong
        if ($tempatLahir === '' && $tanggalLahir === '') {
            return '';
        }
        
        if (empty($tempatLahir) && empty($tanggalLahir)) {
            return 'Belum Diisi Pada Profil';
        } elseif (empty($tempatLahir)) {
            return 'Belum Diisi Pada Profil / ' . $tanggalLahir;
        } elseif (empty($tanggalLahir)) {
            return $tempatLahir . ' / Belum Diisi Pada Profil';
        } else {
            return $tempatLahir . ' / ' . $tanggalLahir;
        }
    }
    
    /**
     * Helper function untuk mendapatkan nilai dengan pesan default
     */
    private function getProfileValue($profile, $key, $defaultMessage = 'Belum Diisi Pada Profil')
    {
        // Jika value adalah string kosong eksplisit (untuk template), return kosong
        if (isset($profile[$key]) && $profile[$key] === '') {
            return '';
        }
        return !empty($profile[$key]) ? $profile[$key] : $defaultMessage;
    }

    /**
     * Ambil data Kepala Desa dari tabel perangkat_desa (case-insensitive)
     */
    private function getKepalaDesa(): array
    {
        $perangkatModel = new PerangkatDesaModel();
        $kepala = $perangkatModel
            ->where('LOWER(jabatan)', 'kepala desa')
            ->first();
        return $kepala ?? [];
    }

    /**
     * Generate Word untuk Surat Keterangan Usaha dari surat masuk
     */
    public function generateWordFromLetter($id)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        $letterModel = new LetterModel();
        $profileModel = new UserProfileModel();
        
        $letter = $letterModel->find($id);
        if (!$letter) {
            return redirect()->to('/staff/surat')->with('error', 'Surat tidak ditemukan.');
        }

        // Cek tipe surat
        if ($letter['tipe_surat'] !== 'Keterangan Usaha' && $letter['tipe_surat'] !== 'Keterangan Tidak Mampu' && $letter['tipe_surat'] !== 'Keterangan Belum Menikah' && $letter['tipe_surat'] !== 'Keterangan Domisili' && $letter['tipe_surat'] !== 'Undangan') {
            return redirect()->to('/staff/surat/' . $id)->with('error', 'Export hanya tersedia untuk Surat Keterangan Usaha, Keterangan Tidak Mampu, Keterangan Belum Menikah, Keterangan Domisili, dan Undangan.');
        }

        // Ambil profil pengirim (user yang mengirim surat)
        $senderProfile = $profileModel->find($letter['user_id']);
        if (!$senderProfile) {
            return redirect()->to('/staff/surat/' . $id)->with('error', 'Profil pengirim tidak ditemukan.');
        }

        // Ambil profil staff yang login (yang menerangkan)
        $staffProfile = $profileModel->find($this->currentUser['id']);
        if (!$staffProfile) {
            return redirect()->to('/staff/surat/' . $id)->with('error', 'Profil staff tidak ditemukan.');
        }

        // Format tanggal Indonesia
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tanggalSekarang = date('d') . ' ' . $bulan[(int)date('m') - 1] . ' ' . date('Y');
        
        // Format tanggal lahir pengirim
        $tempatTanggalLahirSender = $this->formatTempatTanggalLahir($senderProfile, $bulan);

        // Judul surat berdasarkan tipe
        if ($letter['tipe_surat'] === 'Keterangan Tidak Mampu') {
            $judulSurat = 'SURAT KETERANGAN TIDAK MAMPU';
        } elseif ($letter['tipe_surat'] === 'Keterangan Belum Menikah') {
            $judulSurat = 'KETERANGAN BELUM MENIKAH';
        } elseif ($letter['tipe_surat'] === 'Keterangan Domisili') {
            $judulSurat = 'SURAT KETERANGAN DOMISILI';
        } elseif ($letter['tipe_surat'] === 'Undangan') {
            $judulSurat = 'SURAT UNDANGAN';
        } else {
            $judulSurat = 'SURAT KETERANGAN USAHA';
        }

        // Ambil data Kepala Desa
        $kepalaDesa    = $this->getKepalaDesa();
        $namaKepala    = !empty($kepalaDesa['nama'])    ? $kepalaDesa['nama']    : 'Kepala Desa';
        $jabatanKepala = !empty($kepalaDesa['jabatan']) ? $kepalaDesa['jabatan'] : 'Kepala Desa';
        $alamatKepala  = 'Desa Bonto Marannu Kec.Ulu Ere Kab. Bantaeng';

        // Create new PHPWord object
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 1134,    // 2 cm
            'marginBottom' => 1134, // 2 cm
            'marginLeft' => 1417,   // 2.5 cm
            'marginRight' => 1134,  // 2 cm
            'pageSizeW' => 12240,   // 21.5 cm in twips (21.5 * 567)
            'pageSizeH' => 18810,   // 33 cm in twips (33 * 570)
        ]);

        // Header dengan logo dan teks (untuk semua tipe surat)
            $headerTable = $section->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'cellMargin' => 0,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            ]);
            $headerRow = $headerTable->addRow();
            
            // Logo cell (kiri)
            $logoCell = $headerRow->addCell(1200, [
                'valign' => 'center',
                'borderTopSize' => 0,
                'borderTopColor' => 'FFFFFF',
                'borderBottomSize' => 0,
                'borderBottomColor' => 'FFFFFF',
                'borderLeftSize' => 0,
                'borderLeftColor' => 'FFFFFF',
                'borderRightSize' => 0,
                'borderRightColor' => 'FFFFFF',
            ]);
            $logoPath = FCPATH . 'assets/img/logo.webp';
            if (file_exists($logoPath)) {
                $tempPngPath = WRITEPATH . 'uploads/temp/logo_word_temp.png';
                if (!is_dir(WRITEPATH . 'uploads/temp')) {
                    mkdir(WRITEPATH . 'uploads/temp', 0755, true);
                }
                
                if (function_exists('imagecreatefromwebp')) {
                    $image = imagecreatefromwebp($logoPath);
                    if ($image !== false) {
                        $origWidth = imagesx($image);
                        $origHeight = imagesy($image);
                        $size = max($origWidth, $origHeight);
                        $squareImage = imagecreatetruecolor($size, $size);
                        imagealphablending($squareImage, false);
                        imagesavealpha($squareImage, true);
                        $transparent = imagecolorallocatealpha($squareImage, 0, 0, 0, 127);
                        imagefill($squareImage, 0, 0, $transparent);
                        $x = ($size - $origWidth) / 2;
                        $y = ($size - $origHeight) / 2;
                        imagealphablending($squareImage, true);
                        imagecopy($squareImage, $image, $x, $y, 0, 0, $origWidth, $origHeight);
                        imagealphablending($squareImage, false);
                        imagesavealpha($squareImage, true);
                        imagepng($squareImage, $tempPngPath);
                        imagedestroy($image);
                        imagedestroy($squareImage);
                        $logoPath = $tempPngPath;
                    }
                }
                
                $logoCell->addImage($logoPath, [
                    'width' => 68,
                    'height' => 68,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                    'wrappingStyle' => 'inline',
                    'positioning' => 'relative',
                ]);
            }
            
            // Teks header (tengah)
            $textCell = $headerRow->addCell(8800, [
                'valign' => 'center',
                'borderTopSize' => 0,
                'borderTopColor' => 'FFFFFF',
                'borderBottomSize' => 0,
                'borderBottomColor' => 'FFFFFF',
                'borderLeftSize' => 0,
                'borderLeftColor' => 'FFFFFF',
                'borderRightSize' => 0,
                'borderRightColor' => 'FFFFFF',
            ]);
            $textCell->addText('PEMERINTAH DESA BONTO MARANNU', 
                ['bold' => true, 'size' => 16, 'name' => 'Bookman Old Style'], 
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $textCell->addText('KECAMATAN ULU ERE', 
                ['bold' => true, 'size' => 16, 'name' => 'Bookman Old Style'], 
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $textCell->addText('KABUPATEN BANTAENG', 
                ['bold' => true, 'size' => 14, 'name' => 'Bookman Old Style'], 
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
            $textCell->addText('Jln. Pendidikan Loka Desa Bonto Marannu Kec.Ulu Ere Kab.Bantaeng Kode Pos 92451', 
                ['size' => 9, 'name' => 'Bookman Old Style', 'italic' => true], 
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100]);
            
            // Garis pemisah
            $section->addLine([
                'weight' => 2,
                'width' => 480,
                'height' => 0,
            ]);
            $section->addTextBreak(0.5);

            // Judul surat (hanya untuk surat keterangan, bukan undangan)
            if ($letter['tipe_surat'] !== 'Undangan') {
                $section->addText($judulSurat, 
                    ['bold' => true, 'size' => 12, 'underline' => 'single', 'name' => 'Bookman Old Style'], 
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);

                // Nomor (di bawah judul, tengah)
                $section->addText('Nomor :', 
                    ['size' => 11, 'name' => 'Bookman Old Style'], 
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 240]);
            }

        // Handle berdasarkan tipe surat
        if ($letter['tipe_surat'] === 'Keterangan Tidak Mampu') {
            // Format untuk Keterangan Tidak Mampu
            $this->generateKeteranganTidakMampu($section, $senderProfile, $tempatTanggalLahirSender, $tanggalSekarang, $bulan, $namaKepala, $jabatanKepala, $alamatKepala);
        } elseif ($letter['tipe_surat'] === 'Keterangan Belum Menikah') {
            // Format untuk Keterangan Belum Menikah
            $this->generateKeteranganBelumMenikah($section, $senderProfile, $tempatTanggalLahirSender, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala);
        } elseif ($letter['tipe_surat'] === 'Keterangan Domisili') {
            // Format untuk Keterangan Domisili
            $this->generateKeteranganDomisili($section, $senderProfile, $tempatTanggalLahirSender, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala);
        } elseif ($letter['tipe_surat'] === 'Undangan') {
            // Format untuk Undangan
            $this->generateUndangan($section, $tanggalSekarang, $namaKepala, $jabatanKepala);
        } else {
            // Format untuk Keterangan Usaha
            $this->generateKeteranganUsaha($section, $senderProfile, $tempatTanggalLahirSender, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala);
        }

        // Save file
        $filename = 'Surat_' . str_replace(' ', '_', $letter['tipe_surat']) . '_' . $letter['kode_unik'] . '.docx';
        $filepath = WRITEPATH . 'uploads/temp/' . $filename;
        
        if (!is_dir(WRITEPATH . 'uploads/temp')) {
            mkdir(WRITEPATH . 'uploads/temp', 0755, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filepath);

        return $this->response->download($filepath, null)->setFileName($filename);
    }

    /**
     * Generate format Keterangan Usaha
     */
    private function generateKeteranganUsaha($section, $senderProfile, $tempatTanggalLahirSender, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala)
    {
        // Yang bertanda tangan
        $section->addText('Yang bertanda tangan dibawah ini :', 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['spaceAfter' => 120]);

        // Data staff yang menerangkan (dengan indentasi)
        $staffTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
        ]);
        
        $cellStyleNoBorder = [
            'borderTopSize' => 0,
            'borderTopColor' => 'FFFFFF',
            'borderBottomSize' => 0,
            'borderBottomColor' => 'FFFFFF',
            'borderLeftSize' => 0,
            'borderLeftColor' => 'FFFFFF',
            'borderRightSize' => 0,
            'borderRightColor' => 'FFFFFF',
        ];
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Nama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($namaKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Jabatan', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($jabatanKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Alamat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($alamatKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);


        // Menerangkan bahwa
        $section->addText('Menerangkan bahwa :', 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['spaceAfter' => 120]);

        // Data pengirim yang diterangkan (dengan indentasi)
        $senderTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
        ]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Nama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'nama_lengkap'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Tempat/ Tanggal Lahir', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($tempatTanggalLahirSender, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Jenis Kelamin', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'jenis_kelamin', 'Belum Diisi'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Alamat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'alamat'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('No NIK', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'nik'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

        $section->addTextBreak(1);

        // Isi surat keterangan usaha (dengan 11 spasi manual di awal dan rata kiri kanan)
        $isiKeterangan = '           Yang tersebut namanya diatas adalah benar mempunyai Usaha (Nama Usaha) berdiri Sejak tahun (Tahun) sampai sekarang, yang terletak di Dusun (Nama Dusun) Desa Bonto Marannu Kecamatan Ujung Loe Kabupaten Bulukumba.';
        $section->addText($isiKeterangan, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 120]);

        // Penutup (dengan 11 spasi manual di awal dan rata kiri kanan)
        $penutup = '           Demikian Surat keterangan usaha ini diberikan untuk dipergunakan sebagaimana mestinya.';
        $section->addText($penutup, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 360]);

        // Tanda tangan — tempat & tanggal (rata kanan, langsung diikuti jabatan)
        $section->addText('Bonto Marannu, ' . $tanggalSekarang, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 0]);
        
        // Jabatan penandatangan (di bawah tanggal, jeda tanda tangan sesudah jabatan)
        $section->addText($jabatanKepala, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 1400]);
        
        // Nama penandatangan (rata kanan)
        $section->addText($namaKepala, 
            ['size' => 11, 'name' => 'Bookman Old Style', 'underline' => 'single'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
    }

    /**
     * Generate format Keterangan Tidak Mampu
     */
    private function generateKeteranganTidakMampu($section, $senderProfile, $tempatTanggalLahirSender, $tanggalSekarang, $bulan, $namaKepala, $jabatanKepala, $alamatKepala)
    {
        $cellStyleNoBorder = [
            'borderTopSize' => 0,
            'borderTopColor' => 'FFFFFF',
            'borderBottomSize' => 0,
            'borderBottomColor' => 'FFFFFF',
            'borderLeftSize' => 0,
            'borderLeftColor' => 'FFFFFF',
            'borderRightSize' => 0,
            'borderRightColor' => 'FFFFFF',
        ];

        // Yang bertanda tangan di bawah ini menerangkang bahwa pada
        $section->addText('Yang bertanda tangan di bawah ini menerangkang bahwa pada :', 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['spaceAfter' => 120]);

        // Data staff yang menerangkan
        $staffTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
        ]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Nama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($namaKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Jabatan', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($jabatanKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Alamat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($alamatKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);


        // Yang Menerangkan Bahwa
        $section->addText('Yang Menerangkan Bahwa :', 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['spaceAfter' => 120]);

        // Data pengirim yang diterangkan
        $senderTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
        ]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Nama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'nama_lengkap'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Tempat/ Tgl Lahir', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($tempatTanggalLahirSender, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Jenis Kelamin', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'jenis_kelamin', 'Belum Diisi'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Agama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'agama'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('NIK', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'nik'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Pekerjaan', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'pekerjaan'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Alamat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'alamat'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);


        $section->addTextBreak(1);

        // Isi surat
        $isiKeterangan = '           Yang namanya diatas benar warga Desa Bonto Marannu Kecamatan Ulu Ere Kabupaten Bantaeng yang termasuk Kelurga Tidak Mampu/Miskin yang dimaksud dalam data BPS Tahun 2009.';
        $section->addText($isiKeterangan, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 120]);

        // Penutup
        $penutup = '           Demikian Surat Keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.';
        $section->addText($penutup, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 360]);

        // Tanda tangan — tempat & tanggal (rata kanan, langsung diikuti jabatan)
        $section->addText('Bonto Marannu, ' . $tanggalSekarang, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 0]);
        
        // Jabatan penandatangan (di bawah tanggal, jeda tanda tangan sesudah jabatan)
        $section->addText($jabatanKepala, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 1400]);
        
        // Nama penandatangan (rata kanan)
        $section->addText($namaKepala, 
            ['size' => 11, 'name' => 'Bookman Old Style', 'underline' => 'single'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
    }

    /**
     * Generate format Keterangan Belum Menikah
     */
    private function generateKeteranganBelumMenikah($section, $senderProfile, $tempatTanggalLahirSender, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala)
    {
        $cellStyleNoBorder = [
            'borderTopSize' => 0,
            'borderTopColor' => 'FFFFFF',
            'borderBottomSize' => 0,
            'borderBottomColor' => 'FFFFFF',
            'borderLeftSize' => 0,
            'borderLeftColor' => 'FFFFFF',
            'borderRightSize' => 0,
            'borderRightColor' => 'FFFFFF',
        ];

        // Yang bertanda tangan di bawah ini
        $section->addText('Yang bertanda tangan di bawah ini :', 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['spaceAfter' => 120]);

        // Data staff yang menerangkan
        $staffTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
        ]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Nama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($namaKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Jabatan', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($jabatanKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Alamat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($alamatKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

        $section->addTextBreak(1);

        // Menerangkan Bahwa
        $section->addText('Menerangkan Bahwa :', 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['spaceAfter' => 120]);

        // Data pengirim yang diterangkan
        $senderTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
        ]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Nama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'nama_lengkap'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Tempat/Tgl Lahir', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($tempatTanggalLahirSender, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Agama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'agama'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Pekerjaan', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'pekerjaan'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Alamat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'alamat'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

        $section->addTextBreak(1);

        // Isi surat
        $isiKeterangan = '           Yang namanya diatas benar warga Desa Bonto Marannu Kecamatan Ulu Ere Kabupaten Bantaeng yang belum menikah.';
        $section->addText($isiKeterangan, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 120]);

        // Penutup
        $penutup = '           Demikian surat keterangan ini kami buat dan di berikan kepadanya untuk di pergunakan sebagaimana mestinya';
        $section->addText($penutup, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 360]);

        // Tanda tangan — tempat & tanggal (rata kanan, langsung diikuti jabatan)
        $section->addText('Bonto Marannu, ' . $tanggalSekarang, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 0]);
        
        // Jabatan penandatangan (di bawah tanggal, jeda tanda tangan sesudah jabatan)
        $section->addText($jabatanKepala, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 1400]);
        
        // Nama penandatangan (rata kanan)
        $section->addText($namaKepala, 
            ['size' => 11, 'name' => 'Bookman Old Style', 'underline' => 'single'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
    }

    /**
     * Generate format Keterangan Domisili
     */
    private function generateKeteranganDomisili($section, $senderProfile, $tempatTanggalLahirSender, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala)
    {
        $cellStyleNoBorder = [
            'borderTopSize' => 0,
            'borderTopColor' => 'FFFFFF',
            'borderBottomSize' => 0,
            'borderBottomColor' => 'FFFFFF',
            'borderLeftSize' => 0,
            'borderLeftColor' => 'FFFFFF',
            'borderRightSize' => 0,
            'borderRightColor' => 'FFFFFF',
        ];

        // Yang bertanda tangan dibawah ini
        $section->addText('Yang bertanda tangan dibawah ini:', 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['spaceAfter' => 120]);

        // Data staff yang menerangkan
        $staffTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
        ]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Nama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($namaKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Jabatan', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($jabatanKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $staffTable->addRow();
        $cell0 = $staffTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $staffTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Alamat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $staffTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $staffTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($alamatKepala, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

        $section->addTextBreak(1);

        // Menerangkan Bahwa
        $section->addText('Menerangkan Bahwa :', 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['spaceAfter' => 120]);

        // Data pengirim yang diterangkan
        $senderTable = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
        ]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Nama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'nama_lengkap'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Tempat/Tgl lahir', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($tempatTanggalLahirSender, ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Agama', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'agama'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Pekerjaan', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'pekerjaan'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        
        $senderTable->addRow();
        $cell0 = $senderTable->addCell(700, $cellStyleNoBorder);
        $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell1 = $senderTable->addCell(2500, $cellStyleNoBorder);
        $cell1->addText('Alamat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell2 = $senderTable->addCell(300, $cellStyleNoBorder);
        $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
        $cell3 = $senderTable->addCell(6000, $cellStyleNoBorder);
        $cell3->addText($this->getProfileValue($senderProfile, 'alamat'), ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

        $section->addTextBreak(1);

        // Isi surat
        $isiKeterangan = '           Orang tersebut disaksikan dengan sebenarnya bahwa ia penduduk Dusun Latamba ,Desa Bonto Marannu Kecamatan Ujung Loe Kabupaten Bulukumba.';
        $section->addText($isiKeterangan, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 120]);

        // Penutup
        $penutup = '           Demikian Surat Keterangan Domisili ini kami buat dengan sebenarnya untuk dipergunakan sebagai mana mestinya.';
        $section->addText($penutup, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 360]);

        // Tanda tangan — tempat & tanggal (rata kanan, langsung diikuti jabatan)
        $section->addText('Bonto Marannu, ' . $tanggalSekarang, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 0]);
        
        // Jabatan penandatangan (di bawah tanggal, jeda tanda tangan sesudah jabatan)
        $section->addText($jabatanKepala, 
            ['size' => 11, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 1400]);
        
        // Nama penandatangan (rata kanan)
        $section->addText($namaKepala, 
            ['size' => 11, 'name' => 'Bookman Old Style', 'underline' => 'single'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
    }

    /**
 * Generate format Undangan
 */
private function generateUndangan($section, $tanggalSekarang, $namaKepala = '', $jabatanKepala = '')
{
    $cellStyleNoBorder = [
        'borderTopSize' => 0,
        'borderTopColor' => 'FFFFFF',
        'borderBottomSize' => 0,
        'borderBottomColor' => 'FFFFFF',
        'borderLeftSize' => 0,
        'borderLeftColor' => 'FFFFFF',
        'borderRightSize' => 0,
        'borderRightColor' => 'FFFFFF',
    ];

    // Bonto Marannu, tanggal (kanan atas)
    $section->addText('Bonto Marannu, ' . $tanggalSekarang, 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 240]);

    // Nomor, Sifat, Lampiran, Perihal
    $infoTable = $section->addTable([
        'borderSize' => 0,
        'cellMargin' => 0,
    ]);

    $infoTable->addRow();
    $cell1 = $infoTable->addCell(2000, $cellStyleNoBorder);
    $cell1->addText('Nomor', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell2 = $infoTable->addCell(300, $cellStyleNoBorder);
    $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell3 = $infoTable->addCell(6000, $cellStyleNoBorder);
    $cell3->addText('(ISI DISINI)', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

    $infoTable->addRow();
    $cell1 = $infoTable->addCell(2000, $cellStyleNoBorder);
    $cell1->addText('Sifat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell2 = $infoTable->addCell(300, $cellStyleNoBorder);
    $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell3 = $infoTable->addCell(6000, $cellStyleNoBorder);
    $cell3->addText('(ISI DISINI)', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

    $infoTable->addRow();
    $cell1 = $infoTable->addCell(2000, $cellStyleNoBorder);
    $cell1->addText('Lampiran', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell2 = $infoTable->addCell(300, $cellStyleNoBorder);
    $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell3 = $infoTable->addCell(6000, $cellStyleNoBorder);
    $cell3->addText('(ISI DISINI)', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

    $infoTable->addRow();
    $cell1 = $infoTable->addCell(2000, $cellStyleNoBorder);
    $cell1->addText('Perihal', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell2 = $infoTable->addCell(300, $cellStyleNoBorder);
    $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell3 = $infoTable->addCell(6000, $cellStyleNoBorder);
    $cell3->addText('(ISI DISINI)', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

    $section->addTextBreak(1);

    // Kepada - DIUBAH: Indentasi diperbesar dari 700 menjadi 850
    $section->addText('Kepada', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['left' => 850], 'spaceAfter' => 120]);
    
    $section->addText('Yth.', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['left' => 850], 'spaceAfter' => 120]);

    // Daftar penerima - DIUBAH: Indentasi diperbesar dari 700 menjadi 850
    $section->addText('1.         ( ISI DISINI)', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['left' => 850], 'spaceAfter' => 60]);
    
    $section->addText('2.         ( ISI DISINI)', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['left' => 850], 'spaceAfter' => 60]);
    
    $section->addText('3.         ( ISI DISINI)', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['left' => 850], 'spaceAfter' => 60]);
    
    $section->addText('4.         ( ISI DISINI)', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['left' => 850], 'spaceAfter' => 120]);

    // Di - dan Tempat - DIUBAH: Indentasi diperbesar dari 700 menjadi 2000
    $section->addText('Di –', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['left' => 2000], 'spaceAfter' => 60]);
    
    $section->addText('Tempat', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['left' => 2000], 'spaceAfter' => 240]);

    // Isi surat
    $isiSurat = '         ISI DISINI , maka kami pandang perlu untuk mengundang bapak/ibu saudara/(i) untuk menghadiri perihal tersebut diatas yang akan dilaksanakan pada hari :';
    $section->addText($isiSurat, 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 240]);

    // Detail acara
    $acaraTable = $section->addTable([
        'borderSize' => 0,
        'cellMargin' => 0,
    ]);

    $acaraTable->addRow();
    $cell0 = $acaraTable->addCell(700, $cellStyleNoBorder);
    $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell1 = $acaraTable->addCell(2500, $cellStyleNoBorder);
    $cell1->addText('Hari/Tanggal', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell2 = $acaraTable->addCell(300, $cellStyleNoBorder);
    $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell3 = $acaraTable->addCell(6000, $cellStyleNoBorder);
    $cell3->addText('ISI DISINI', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

    $acaraTable->addRow();
    $cell0 = $acaraTable->addCell(700, $cellStyleNoBorder);
    $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell1 = $acaraTable->addCell(2500, $cellStyleNoBorder);
    $cell1->addText('Jam', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell2 = $acaraTable->addCell(300, $cellStyleNoBorder);
    $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell3 = $acaraTable->addCell(6000, $cellStyleNoBorder);
    $cell3->addText('ISI DISINI', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

    $acaraTable->addRow();
    $cell0 = $acaraTable->addCell(700, $cellStyleNoBorder);
    $cell0->addText('', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell1 = $acaraTable->addCell(2500, $cellStyleNoBorder);
    $cell1->addText('Tempat', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell2 = $acaraTable->addCell(300, $cellStyleNoBorder);
    $cell2->addText(':', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);
    $cell3 = $acaraTable->addCell(6000, $cellStyleNoBorder);
    $cell3->addText('ISI DISINI', ['size' => 11, 'name' => 'Bookman Old Style'], ['spaceAfter' => 0]);

    $section->addTextBreak(1);

    // Penutup
    $penutup = '         Demikian undangan ini disampaikan untuk mendapatkan perhatian dan atas kehadiran bapak/ibu saudara (i) kami ucapkan terimaksih.';
    $section->addText($penutup, 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 360]);

    // Tanda tangan — jabatan di bawah penutup, jeda tanda tangan sesudah jabatan
    $section->addText(($jabatanKepala ?: 'Kepala Desa') . ',', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 1400]);

    // Nama penandatangan (rata kanan, bergaris bawah)
    $section->addText(($namaKepala ?: '( ISI DISINI)'), 
        ['size' => 11, 'name' => 'Bookman Old Style', 'underline' => 'single'], 
        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter' => 240]);

    // Tembusan
    $section->addText('Tembusan :', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['spaceAfter' => 120]);
    
    $section->addText('1.  Arsip', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['firstLine' => 700], 'spaceAfter' => 60]);
    
    $section->addText('2.  ISI DISINI', 
        ['size' => 11, 'name' => 'Bookman Old Style'], 
        ['indentation' => ['firstLine' => 700], 'spaceAfter' => 0]);
}

    /**
     * Download Template Surat (dengan nama kosong)
     */
    public function downloadTemplate($tipe)
    {
        if ($redirect = $this->guard(['staf'])) {
            return $redirect;
        }

        // Mapping tipe surat
        $tipeMapping = [
            'keterangan-usaha' => 'Keterangan Usaha',
            'keterangan-tidak-mampu' => 'Keterangan Tidak Mampu',
            'keterangan-belum-menikah' => 'Keterangan Belum Menikah',
            'keterangan-domisili' => 'Keterangan Domisili',
            'undangan' => 'Undangan'
        ];

        if (!isset($tipeMapping[$tipe])) {
            return redirect()->to('/staff/surat')->with('error', 'Template tidak ditemukan.');
        }

        $tipeSurat = $tipeMapping[$tipe];

        // Ambil profil staff yang login (untuk data staff yang menerangkan)
        $profileModel = new UserProfileModel();
        $staffProfile = $profileModel->find($this->currentUser['id']);
        if (!$staffProfile) {
            return redirect()->to('/staff/surat')->with('error', 'Profil staff tidak ditemukan.');
        }

        // Ambil data Kepala Desa
        $kepalaDesa    = $this->getKepalaDesa();
        $namaKepala    = !empty($kepalaDesa['nama'])    ? $kepalaDesa['nama']    : 'Kepala Desa';
        $jabatanKepala = !empty($kepalaDesa['jabatan']) ? $kepalaDesa['jabatan'] : 'Kepala Desa';
        $alamatKepala  = 'Desa Bonto Marannu Kec.Ulu Ere Kab. Bantaeng';

        // Format tanggal Indonesia
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tanggalSekarang = date('d') . ' ' . $bulan[(int)date('m') - 1] . ' ' . date('Y');

        // Buat profile kosong untuk template
        $emptyProfile = [
            'nama_lengkap' => '',
            'tempat_lahir' => '',
            'tanggal_lahir' => '',
            'agama' => '',
            'pekerjaan' => '',
            'alamat' => '',
            'nik' => ''
        ];

        // Format tempat/tanggal lahir kosong
        $tempatTanggalLahirKosong = '';

        // Judul surat berdasarkan tipe
        if ($tipeSurat === 'Keterangan Tidak Mampu') {
            $judulSurat = 'SURAT KETERANGAN TIDAK MAMPU';
        } elseif ($tipeSurat === 'Keterangan Belum Menikah') {
            $judulSurat = 'KETERANGAN BELUM MENIKAH';
        } elseif ($tipeSurat === 'Keterangan Domisili') {
            $judulSurat = 'SURAT KETERANGAN DOMISILI';
        } elseif ($tipeSurat === 'Undangan') {
            $judulSurat = 'SURAT UNDANGAN';
        } else {
            $judulSurat = 'SURAT KETERANGAN USAHA';
        }

        // Create new PHPWord object
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 1134,    // 2 cm
            'marginBottom' => 1134, // 2 cm
            'marginLeft' => 1417,   // 2.5 cm
            'marginRight' => 1134,  // 2 cm
            'pageSizeW' => 12240,   // 21.5 cm in twips (21.5 * 567)
            'pageSizeH' => 18810,   // 33 cm in twips (33 * 570)
        ]);

        // Header dengan logo dan teks (untuk semua tipe surat)
        $headerTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 0,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ]);
        $headerRow = $headerTable->addRow();
        
        // Logo cell (kiri)
        $logoCell = $headerRow->addCell(1200, [
            'valign' => 'center',
            'borderTopSize' => 0,
            'borderTopColor' => 'FFFFFF',
            'borderBottomSize' => 0,
            'borderBottomColor' => 'FFFFFF',
            'borderLeftSize' => 0,
            'borderLeftColor' => 'FFFFFF',
            'borderRightSize' => 0,
            'borderRightColor' => 'FFFFFF',
        ]);
        $logoPath = FCPATH . 'assets/img/logo.webp';
        if (file_exists($logoPath)) {
            $tempPngPath = WRITEPATH . 'uploads/temp/logo_word_temp.png';
            if (!is_dir(WRITEPATH . 'uploads/temp')) {
                mkdir(WRITEPATH . 'uploads/temp', 0755, true);
            }
            
            if (function_exists('imagecreatefromwebp')) {
                $image = imagecreatefromwebp($logoPath);
                if ($image !== false) {
                    $origWidth = imagesx($image);
                    $origHeight = imagesy($image);
                    $size = max($origWidth, $origHeight);
                    $squareImage = imagecreatetruecolor($size, $size);
                    imagealphablending($squareImage, false);
                    imagesavealpha($squareImage, true);
                    $transparent = imagecolorallocatealpha($squareImage, 0, 0, 0, 127);
                    imagefill($squareImage, 0, 0, $transparent);
                    $x = ($size - $origWidth) / 2;
                    $y = ($size - $origHeight) / 2;
                    imagealphablending($squareImage, true);
                    imagecopy($squareImage, $image, $x, $y, 0, 0, $origWidth, $origHeight);
                    imagealphablending($squareImage, false);
                    imagesavealpha($squareImage, true);
                    imagepng($squareImage, $tempPngPath);
                    imagedestroy($image);
                    imagedestroy($squareImage);
                    $logoPath = $tempPngPath;
                }
            }
            
            $logoCell->addImage($logoPath, [
                'width' => 68,
                'height' => 68,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                'wrappingStyle' => 'inline',
                'positioning' => 'relative',
            ]);
        }
        
        // Teks header (tengah)
        $textCell = $headerRow->addCell(8800, [
            'valign' => 'center',
            'borderTopSize' => 0,
            'borderTopColor' => 'FFFFFF',
            'borderBottomSize' => 0,
            'borderBottomColor' => 'FFFFFF',
            'borderLeftSize' => 0,
            'borderLeftColor' => 'FFFFFF',
            'borderRightSize' => 0,
            'borderRightColor' => 'FFFFFF',
        ]);
        $textCell->addText('PEMERINTAH DESA BONTO MARANNU', 
            ['bold' => true, 'size' => 16, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $textCell->addText('KECAMATAN ULU ERE', 
            ['bold' => true, 'size' => 16, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $textCell->addText('KABUPATEN BANTAENG', 
            ['bold' => true, 'size' => 14, 'name' => 'Bookman Old Style'], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);
        $textCell->addText('Jln. Pendidikan Loka Desa Bonto Marannu Kec.Ulu Ere Kab.Bantaeng Kode Pos 92451', 
            ['size' => 9, 'name' => 'Bookman Old Style', 'italic' => true], 
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100]);
        
        // Garis pemisah
        $section->addLine([
            'weight' => 2,
            'width' => 480,
            'height' => 0,
        ]);
        $section->addTextBreak(0.5);

        // Judul surat (hanya untuk surat keterangan, bukan undangan)
        if ($tipeSurat !== 'Undangan') {
            $section->addText($judulSurat, 
                ['bold' => true, 'size' => 12, 'underline' => 'single', 'name' => 'Bookman Old Style'], 
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0]);

            // Nomor (di bawah judul, tengah)
            $section->addText('Nomor :', 
                ['size' => 11, 'name' => 'Bookman Old Style'], 
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 240]);
        }

        // Handle berdasarkan tipe surat dengan profile kosong
        if ($tipeSurat === 'Keterangan Tidak Mampu') {
            $this->generateKeteranganTidakMampu($section, $emptyProfile, $tempatTanggalLahirKosong, $tanggalSekarang, $bulan, $namaKepala, $jabatanKepala, $alamatKepala);
        } elseif ($tipeSurat === 'Keterangan Belum Menikah') {
            $this->generateKeteranganBelumMenikah($section, $emptyProfile, $tempatTanggalLahirKosong, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala);
        } elseif ($tipeSurat === 'Keterangan Domisili') {
            $this->generateKeteranganDomisili($section, $emptyProfile, $tempatTanggalLahirKosong, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala);
        } elseif ($tipeSurat === 'Undangan') {
            // Format untuk Undangan
            $this->generateUndangan($section, $tanggalSekarang, $namaKepala, $jabatanKepala);
        } else {
            // Format untuk Keterangan Usaha
            $this->generateKeteranganUsaha($section, $emptyProfile, $tempatTanggalLahirKosong, $tanggalSekarang, $namaKepala, $jabatanKepala, $alamatKepala);
        }

        // Save file
        $filename = 'Template_' . str_replace(' ', '_', $tipeSurat) . '.docx';
        $filepath = WRITEPATH . 'uploads/temp/' . $filename;
        
        if (!is_dir(WRITEPATH . 'uploads/temp')) {
            mkdir(WRITEPATH . 'uploads/temp', 0755, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filepath);

        return $this->response->download($filepath, null)->setFileName($filename);
    }

}
