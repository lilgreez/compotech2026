<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public $excelPath;

    public function __construct($excelPath)
    {
        $this->excelPath = $excelPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Minimum Stock Dieset - ' . date('d-m-Y H:i'),
        );
    }

    public function content(): Content
    {
        // Kita akan menggunakan view HTML sederhana yang digenerate langsung
        return new Content(
            htmlString: '
                <div style="font-family: Arial, sans-serif; color: #333;">
                    <p>Hi,</p>
                    <p>Terlampir Laporan Minimum Stock Dieset Pada Tanggal : <b>' . date('d-m-Y H:i:s') . '</b></p>
                    <p>Silahkan Mendownload File XLS Lampiran.</p>
                    <br>
                    <p>Untuk informasi lebih lanjut, Silahkan mengklik <a href="'.route('parts-stock.index', ['tab' => 'low']).'">alamat ini</a>.</p>
                    <br>
                    <p>Email ini dikirimkan secara otomatis. Harap tidak me-replay email ini.<br>
                    Jika ada kesalahan informasi harap hubungi Admin.</p>
                    <br>
                    <p>Good luck!</p>
                </div>
            '
        );
    }

    public function attachments(): array
    {
        // Melampirkan file Excel yang sudah di-generate
        return[
            Attachment::fromStorage($this->excelPath)
                ->as('LessStockDiesetParts.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}