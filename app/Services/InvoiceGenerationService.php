<?php

namespace App\Services;

use App\Helpers\NumberToStringHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class InvoiceGenerationService
{
    public const TEMPLATE_VELES = 'veles';
    public const TEMPLATE_INCOMSTIL = 'incomstill';
    public const TEMPLATE_INDUSTRIAL = 'industrial';
    private string $template;
    private int $number;
    private int $amount;
    private string $textAmount;
    private string $date;

    public static function getSupportedTemplates(): array
    {
        return [self::TEMPLATE_VELES, self::TEMPLATE_INCOMSTIL, self::TEMPLATE_INDUSTRIAL];
    }

    public static function getTemplateNames(): array
    {
        return [
            self::TEMPLATE_VELES => 'Велес',
            self::TEMPLATE_INCOMSTIL => 'Инком стил',
            self::TEMPLATE_INDUSTRIAL => 'Индустраиальная компания'
        ];
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function generate(int $number, int $amount, $date, string $template): array
    {
        $this->number = $number;
        $this->amount = $amount;
        $this->date = $date;
        $this->setTemplate($template);
        $this->textAmount = NumberToStringHelper::convert($amount);

        $urls = [];
        $urls[] = $this->makeDocument('invoice');
        $urls[] = $this->makeDocument('act');

        return $urls;
    }

    public function setTemplate(string $template): void
    {
        $template = Str::lower($template);
        if(Str::contains($template, 'велес')) {
            $template = self::TEMPLATE_VELES;
        }
        if(Str::contains($template, 'инком')) {
            $template = self::TEMPLATE_INCOMSTIL;
        }
        if(Str::contains($template, 'индустр')) {
            $template = self::TEMPLATE_INDUSTRIAL;
        }

        $this->template = $template;
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    private function makeDocument(string $type): string
    {
        $fileName = $this->getFileName($type);
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        $templatePath = $this->getTemplatePath($type);
        $this->fillTemplate($templatePath, $fileName);
        return $fileName;
    }

    private function getFileName(string $type): string
    {
        $prefix = ($type === 'invoice') ? '' : 'АКТ ';
        return 'invoices/' . $prefix . $this->number . ' ' . date('y') . '.docx';
    }

    private function getTemplatePath(string $type): string
    {
        $suffix = ($type === 'invoice') ? '' : '_act';
        return storage_path('templates/' . $this->template . $suffix . '.docx');
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    private function fillTemplate(string $templatePath, string $saveAs): void
    {
        $invoice = new TemplateProcessor($templatePath);
        $invoice->setValue('number', $this->number);
        $invoice->setValue('amount', $this->amount);
        $invoice->setValue('date', $this->date);
        $invoice->setValue('textAmount', $this->textAmount);

        $invoice->saveAs(storage_path($saveAs));
    }
}
