<?php

namespace App\Http\Controllers;

use App\Models\ChatbotLog;
use App\Models\Faq;
use App\Services\ChatbotProductSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function __construct(private ChatbotProductSearchService $productSearch) {}

    public function ask(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $question = trim($payload['message']);
        $normalized = Str::of($question)->lower()->squish()->value();

        $reply = $this->resolveReply($normalized);

        $this->storeLog($request, $question, $reply);

        return response()->json([
            'ok' => true,
            'data' => $reply,
        ]);
    }

    private function resolveReply(string $question): array
    {
        $faq = $this->matchFaq($question);
        if ($faq) {
            return [
                'intent' => 'faq',
                'answer' => $faq['answer'],
                'confidence' => $faq['confidence'],
                'matched_product_id' => null,
                'matched_faq_id' => $faq['id'],
            ];
        }

        if ($this->isListIntent($question)) {
            $category = $this->extractAfterKeyword($question, 'kategori');
            $brand = $this->extractAfterKeyword($question, 'merek')
                ?? $this->extractAfterKeyword($question, 'brand');

            $items = $this->productSearch->listProducts($category, $brand, 8);
            if ($items->isEmpty()) {
                return [
                    'intent' => 'product_list',
                    'answer' => 'Maaf, produk yang sesuai filter tersebut belum saya temukan.',
                    'confidence' => 68.0,
                    'matched_product_id' => null,
                    'matched_faq_id' => null,
                ];
            }

            $lines = [];
            foreach ($items as $item) {
                $harga = (float) ($item->harga_jual ?? $item->harga ?? 0);
                $status = ((int) $item->stok > 0) ? 'Tersedia' : 'Habis';
                $lines[] = sprintf(
                    '- %s (%s) | Stok: %d | Harga: Rp%s',
                    $item->nama_barang,
                    $status,
                    (int) $item->stok,
                    number_format($harga, 0, ',', '.')
                );
            }

            $prefix = 'Berikut daftar produk yang saya temukan:';
            if ($category) {
                $prefix = 'Berikut produk untuk kategori "'.$category.'":';
            } elseif ($brand) {
                $prefix = 'Berikut produk untuk merek "'.$brand.'":';
            }

            return [
                'intent' => 'product_list',
                'answer' => $prefix."\n".implode("\n", $lines),
                'confidence' => 78.0,
                'matched_product_id' => null,
                'matched_faq_id' => null,
            ];
        }

        $match = $this->productSearch->findBestMatch($question);
        if ($match) {
            $item = $match['product'];
            $score = (float) ($match['score'] ?? 0);
            $stok = (int) $item->stok;
            $harga = (float) ($item->harga_jual ?? $item->harga ?? 0);

            if ($this->isPriceIntent($question)) {
                $answer = sprintf(
                    'Harga %s saat ini Rp%s.',
                    $item->nama_barang,
                    number_format($harga, 0, ',', '.')
                );

                return [
                    'intent' => 'price_check',
                    'answer' => $answer,
                    'confidence' => $score,
                    'matched_product_id' => $item->id,
                    'matched_faq_id' => null,
                ];
            }

            if ($this->isStockIntent($question)) {
                $availability = $stok > 0 ? 'tersedia' : 'sedang habis';
                $answer = sprintf(
                    '%s %s. Stok saat ini: %d unit.',
                    $item->nama_barang,
                    $availability,
                    $stok
                );

                return [
                    'intent' => 'stock_check',
                    'answer' => $answer,
                    'confidence' => $score,
                    'matched_product_id' => $item->id,
                    'matched_faq_id' => null,
                ];
            }

            $availability = $stok > 0 ? 'Tersedia' : 'Sedang habis';
            $answer = sprintf(
                '%s: %s. Stok %d unit, harga Rp%s.',
                $item->nama_barang,
                $availability,
                $stok,
                number_format($harga, 0, ',', '.')
            );

            return [
                'intent' => 'product_info',
                'answer' => $answer,
                'confidence' => $score,
                'matched_product_id' => $item->id,
                'matched_faq_id' => null,
            ];
        }

        return [
            'intent' => 'fallback',
            'answer' => 'Maaf, saya belum menemukan produk yang dimaksud. Coba tulis nama produk, merek, atau kategorinya ya.',
            'confidence' => 40.0,
            'matched_product_id' => null,
            'matched_faq_id' => null,
        ];
    }

    private function matchFaq(string $question): ?array
    {
        if (! Schema::hasTable('faqs')) {
            return $this->matchDefaultFaq($question);
        }

        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'question', 'answer', 'keywords']);

        $best = null;
        $bestScore = 0.0;

        foreach ($faqs as $faq) {
            $score = 0.0;
            $normalizedQuestion = Str::of((string) $faq->question)->lower()->squish()->value();
            if ($normalizedQuestion !== '' && Str::contains($question, $normalizedQuestion)) {
                $score += 70;
            }

            $keywords = array_filter(array_map('trim', explode(',', (string) $faq->keywords)));
            foreach ($keywords as $keyword) {
                $keyword = Str::of($keyword)->lower()->squish()->value();
                if ($keyword !== '' && Str::contains($question, $keyword)) {
                    $score += 18;
                }
            }

            similar_text($question, $normalizedQuestion, $percent);
            $score += min($percent, 30) * 0.5;

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $faq;
            }
        }

        if ($best && $bestScore >= 45) {
            return [
                'id' => $best->id,
                'answer' => $best->answer,
                'confidence' => round($bestScore, 2),
            ];
        }

        return $this->matchDefaultFaq($question);
    }

    private function matchDefaultFaq(string $question): ?array
    {
        $defaults = [
            'cara pesan' => 'Untuk pesan barang: buka katalog, tambah ke keranjang, lalu submit pesanan dari akun customer Anda.',
            'metode pembayaran' => 'Metode pembayaran yang tersedia: transfer bank dan pembayaran sesuai kesepakatan dengan admin.',
            'jam operasional' => 'Jam operasional: Senin-Sabtu, pukul 08.00-17.00.',
            'pengiriman' => 'Pengiriman dilakukan setelah pembayaran terverifikasi. Estimasi pengiriman menyesuaikan lokasi tujuan.',
        ];

        foreach ($defaults as $key => $answer) {
            if (Str::contains($question, $key)) {
                return [
                    'id' => null,
                    'answer' => $answer,
                    'confidence' => 60.0,
                ];
            }
        }

        return null;
    }

    private function isStockIntent(string $question): bool
    {
        return Str::contains($question, ['stok', 'ready', 'tersedia', 'habis']);
    }

    private function isPriceIntent(string $question): bool
    {
        return Str::contains($question, ['harga', 'berapa harganya', 'price']);
    }

    private function isListIntent(string $question): bool
    {
        return Str::contains($question, [
            'daftar produk',
            'list produk',
            'produk apa saja',
            'produk kategori',
            'produk merek',
        ]);
    }

    private function extractAfterKeyword(string $question, string $keyword): ?string
    {
        $pattern = '/\b'.preg_quote($keyword, '/').'\s+([a-z0-9\s\-]+)/i';
        if (! preg_match($pattern, $question, $matches)) {
            return null;
        }

        $value = trim($matches[1] ?? '');
        if ($value === '') {
            return null;
        }

        $cutWords = ['stok', 'harga', 'ready', 'tersedia', 'barang'];
        foreach ($cutWords as $cutWord) {
            $value = preg_replace('/\b'.preg_quote($cutWord, '/').'\b.*/i', '', $value) ?? $value;
        }

        return trim($value) ?: null;
    }

    private function storeLog(Request $request, string $question, array $reply): void
    {
        if (! Schema::hasTable('chatbot_logs')) {
            return;
        }

        try {
            ChatbotLog::create([
                'session_id' => $request->session()->getId(),
                'user_id' => auth()->id(),
                'question' => $question,
                'answer' => (string) ($reply['answer'] ?? ''),
                'intent' => $reply['intent'] ?? null,
                'matched_product_id' => $reply['matched_product_id'] ?? null,
                'matched_faq_id' => $reply['matched_faq_id'] ?? null,
                'confidence' => $reply['confidence'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
            ]);
        } catch (\Throwable $e) {
        }
    }
}
