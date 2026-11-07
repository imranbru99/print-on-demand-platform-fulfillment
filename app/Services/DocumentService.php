<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\File;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;

final class DocumentService
{
    public function storeOrderInvoice(Order $order): string
    {
        $directory = storage_path('app/documents/'.$order->getKey());
        File::ensureDirectoryExists($directory);

        $path = $directory.'/invoice.pdf';

        UnicodePdf::loadView('documents.invoice', ['order' => $order])
            ->save($path);

        return $path;
    }

    public function storePackingSlip(Order $order): string
    {
        $directory = storage_path('app/documents/'.$order->getKey());
        File::ensureDirectoryExists($directory);

        $path = $directory.'/packing-slip.pdf';

        UnicodePdf::loadView('documents.packing-slip', ['order' => $order])
            ->save($path);

        return $path;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function renderPdf(string $view, array $data, string $filename = 'document.pdf'): Response
    {
        return UnicodePdf::loadView($view, $data)
            ->download($filename);
    }
}
