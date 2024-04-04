<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceGenerationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\InvoiceGenerationService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class InvoiceGenerationController extends Controller
{
    public function __construct(
        private readonly InvoiceGenerationService $invoiceGenerationService
    )
    {

    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('invoice', [
            'templates' => InvoiceGenerationService::getTemplateNames()
        ]);
    }

    public function generate(InvoiceGenerationRequest $request): RedirectResponse
    {
        $links = $this->invoiceGenerationService->generate(
            $request->integer('number'),
            $request->integer('amount'),
            $request->string('date'),
            $request->string('template'),
        );

        [$invoicePath, $actPath] = $links;

        $zip = new ZipArchive();
        $zipFileName = 'invoices.zip'; // Имя для ZIP-архива
        if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
            // Добавляем файл счета в архив
            $zip->addFile(storage_path($invoicePath), basename($invoicePath));
            // Добавляем файл акта в архив
            $zip->addFile(storage_path($actPath), basename($actPath));
            $zip->close();
        }

        $zipStream = fopen($zipFileName, 'r');

        $response = new StreamedResponse(function () use ($zipStream) {
            fpassthru($zipStream);
            fclose($zipStream);
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $zipFileName . '"');
        $response->headers->set('Content-Length', filesize($zipFileName));

        unlink($zipFileName); // Удаляем временный ZIP-файл после отправки

        // Возвращаем архив клиенту
        $response->send();

        // Перенаправляем пользователя на другую страницу
        return redirect()->route('invoice.index');
    }
}
