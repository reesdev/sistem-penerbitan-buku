<?php
namespace App\Services;
use App\Models\Author;
use App\Models\Manuscript;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class ManuscriptService
{
    /**
     * Handle manuscript creation and file uploads to Cloudflare R2.
     *
     * @param array $data Data tervalidasi dari SubmitManuscriptRequest
     * @param int $userId ID User yang sedang login (Author)
     * @return Manuscript
     */
    public function createManuscript(array $data, int $userId): Manuscript
    {
        return DB::transaction(function () use ($data, $userId) {
            // 1. Upload File PDF ke Cloudflare R2
            $pdfPath = $data['file_pdf']->store('manuscripts/pdf', 'r2');
            $pdfUrl = Storage::disk('r2')->url($pdfPath);
            // 2. Upload Cover Image ke Cloudflare R2
            $coverPath = $data['cover_image']->store('manuscripts/covers', 'r2');
            $coverUrl = Storage::disk('r2')->url($coverPath);
            // 3. Simpan data ke tabel manuscripts (ERD PRD Bab 7)
            $manuscript = Manuscript::create([
                'user_id'      => $userId,
                'title'        => $data['title'],
                'description'  => $data['description'],
                'file_path'    => $pdfUrl,
                'cover_path'   => $coverUrl,
                'status'       => 'DRAFT', 
                'isbn'         => null,
                'submitted_at' => now(),
            ]);
            // 4. Simpan data detail ke tabel authors (Relasi 1:1)
            Author::create([
                'manuscript_id' => $manuscript->id,
                'author_name'   => $data['author_name'],
                'biodata'       => $data['biodata'],
                'phone_number'  => $data['phone_number'],
            ]);
            // Return beserta relasi author-nya
            return $manuscript->load('author');
        });
    }
}