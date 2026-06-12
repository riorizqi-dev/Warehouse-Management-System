<?php

namespace App\Services;

use App\Models\DataBarang;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ChatbotProductSearchService
{
    public function findBestMatch(string $question): ?array
    {
        $normalizedQuestion = $this->normalize($question);
        if ($normalizedQuestion === '') {
            return null;
        }

        $tokens = $this->extractTokens($normalizedQuestion);
        if (empty($tokens)) {
            return null;
        }

        $candidates = DataBarang::query()
            ->select(['id', 'nama_barang', 'merek', 'kode_barang', 'tipe_model', 'stok', 'harga', 'harga_jual', 'kategori_id'])
            ->with('kategori:id,nama_kategori')
            ->where(function ($q) use ($tokens) {
                foreach ($tokens as $token) {
                    $like = '%'.$token.'%';
                    $q->orWhere('nama_barang', 'like', $like)
                        ->orWhere('merek', 'like', $like)
                        ->orWhere('kode_barang', 'like', $like)
                        ->orWhere('tipe_model', 'like', $like);
                }
            })
            ->limit(50)
            ->get();

        if ($candidates->isEmpty()) {
            return null;
        }

        $best = null;
        $bestScore = 0.0;
        foreach ($candidates as $item) {
            $score = $this->scoreItem($item, $normalizedQuestion, $tokens);
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item;
            }
        }

        if (! $best || $bestScore < 25) {
            return null;
        }

        return [
            'product' => $best,
            'score' => round($bestScore, 2),
        ];
    }

    public function listProducts(?string $category = null, ?string $brand = null, int $limit = 8): Collection
    {
        $query = DataBarang::query()
            ->with('kategori:id,nama_kategori')
            ->orderBy('nama_barang')
            ->limit($limit);

        if ($category) {
            $category = trim($category);
            $query->whereHas('kategori', function ($q) use ($category) {
                $q->where('nama_kategori', 'like', '%'.$category.'%');
            });
        }

        if ($brand) {
            $brand = trim($brand);
            $query->where('merek', 'like', '%'.$brand.'%');
        }

        return $query->get();
    }

    private function scoreItem(DataBarang $item, string $question, array $tokens): float
    {
        $name = $this->normalize((string) $item->nama_barang);
        $brand = $this->normalize((string) $item->merek);
        $code = $this->normalize((string) $item->kode_barang);
        $model = $this->normalize((string) $item->tipe_model);
        $haystack = trim($name.' '.$brand.' '.$code.' '.$model);

        $score = 0.0;

        if ($name !== '' && Str::contains($question, $name)) {
            $score += 55;
        }

        foreach ($tokens as $token) {
            if (Str::contains($haystack, $token)) {
                $score += 9;
            }
        }

        similar_text($question, $name, $namePercent);
        $score += min($namePercent, 35) * 0.8;

        similar_text($question, $haystack, $allPercent);
        $score += min($allPercent, 30) * 0.4;

        return $score;
    }

    private function extractTokens(string $text): array
    {
        $parts = preg_split('/[^a-z0-9]+/i', $text) ?: [];
        $stopwords = [
            'apakah', 'ada', 'barang', 'produk', 'ready', 'stok', 'berapa', 'harga', 'untuk', 'yang',
            'dengan', 'mohon', 'tolong', 'cari', 'di', 'ke', 'dan', 'atau', 'bisa', 'kah', 'saya',
            'list', 'daftar', 'show', 'cek', 'cekin', 'ingin', 'mau', 'apa', 'saja', 'kategori', 'merek',
        ];

        $tokens = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '' || strlen($part) < 2) {
                continue;
            }
            if (in_array($part, $stopwords, true)) {
                continue;
            }
            $tokens[] = $part;
        }

        return array_values(array_unique($tokens));
    }

    private function normalize(string $value): string
    {
        return Str::of($value)->lower()->squish()->value();
    }
}
