<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GalleryQrController extends Controller
{
    /**
     * QR-код на внешнюю ссылку галереи мероприятия.
     *
     * Генерируется на лету: при смене ссылки в админке QR меняется автоматически,
     * файлы не хранятся. Маршрут публичный — payload и так публичная ссылка,
     * а <img> внутри <a download> с auth-редиректом не скачался бы.
     */
    public function show(Event $event): Response
    {
        abort_if(filled($event->gallery_external_url) === false, 404);

        $url = $event->gallery_external_url;

        $headers = [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ];

        if (request()->boolean('download')) {
            $headers['Content-Disposition'] = sprintf(
                'attachment; filename="gallery-qr-%s.png"',
                $event->slug,
            );
        }

        return response(
            QrCode::format('png')->size(400)->margin(2)->errorCorrection('M')->generate($url),
            200,
            $headers,
        );
    }
}
