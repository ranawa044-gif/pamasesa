<?php

namespace App\Services;

class PdfGeneratorService
{
    public function generate(string $html): string
    {
        if (! class_exists(\Dompdf\Dompdf::class)) {
            throw new \RuntimeException('DomPDF class not found. Install dompdf/dompdf to generate PDF files.');
        }

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
