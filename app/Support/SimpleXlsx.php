<?php

namespace App\Support;

use RuntimeException;
use ZipArchive;

class SimpleXlsx
{
    /**
     * Read an .xlsx file into sheets of rows.
     *
     * @return array<string, list<list<string>>>
     */
    public static function read(string $path): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP ZipArchive extension is required to read Excel files.');
        }

        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new RuntimeException('Could not open the Excel file.');
        }

        $sharedStrings = self::parseSharedStrings((string) $zip->getFromName('xl/sharedStrings.xml'));
        $sheetFiles = self::sheetFiles($zip);
        $sheets = [];

        foreach ($sheetFiles as $name => $file) {
            $xml = (string) $zip->getFromName($file);
            if ($xml === '') {
                continue;
            }
            $sheets[$name] = self::parseSheet($xml, $sharedStrings);
        }

        $zip->close();

        return $sheets;
    }

    /**
     * Build a simple .xlsx binary from named sheets.
     *
     * @param  array<string, array{headers: list<string>, rows?: list<list<mixed>>}>  $sheets
     */
    public static function build(array $sheets): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP ZipArchive extension is required to create Excel files.');
        }

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        if ($tmp === false) {
            throw new RuntimeException('Could not create a temporary Excel file.');
        }

        $zip = new ZipArchive();
        if ($zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Could not write the Excel file.');
        }

        $sheetNames = array_keys($sheets);
        $workbookSheets = '';
        $workbookRels = '';
        $contentTypesOverrides = '';

        foreach ($sheetNames as $index => $name) {
            $sheetId = $index + 1;
            $escapedName = self::xml($name);
            $workbookSheets .= '<sheet name="'.$escapedName.'" sheetId="'.$sheetId.'" r:id="rId'.$sheetId.'"/>';
            $workbookRels .= '<Relationship Id="rId'.$sheetId.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet'.$sheetId.'.xml"/>';
            $contentTypesOverrides .= '<Override PartName="/xl/worksheets/sheet'.$sheetId.'.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';

            $headers = $sheets[$name]['headers'] ?? [];
            $rows = $sheets[$name]['rows'] ?? [];
            $zip->addFromString('xl/worksheets/sheet'.$sheetId.'.xml', self::worksheetXml($headers, $rows));
        }

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .$contentTypesOverrides
            .'</Types>');

        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>');

        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .$workbookRels
            .'</Relationships>');

        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets>'.$workbookSheets.'</sheets>'
            .'</workbook>');

        $zip->close();

        $binary = file_get_contents($tmp);
        @unlink($tmp);

        if ($binary === false) {
            throw new RuntimeException('Could not read the generated Excel file.');
        }

        return $binary;
    }

    /**
     * @return array<string, string>
     */
    private static function sheetFiles(ZipArchive $zip): array
    {
        $workbook = self::toSimpleXml((string) $zip->getFromName('xl/workbook.xml'));
        $relsXml = self::toSimpleXml((string) $zip->getFromName('xl/_rels/workbook.xml.rels'));

        $relTargets = [];
        foreach ($relsXml->Relationship ?? [] as $rel) {
            $id = (string) $rel['Id'];
            $target = (string) $rel['Target'];
            if ($id === '' || $target === '') {
                continue;
            }
            $relTargets[$id] = 'xl/'.ltrim(str_replace('\\', '/', $target), '/');
            $relTargets[$id] = preg_replace('#^xl/xl/#', 'xl/', $relTargets[$id]) ?? $relTargets[$id];
        }

        $files = [];
        $index = 1;
        foreach ($workbook->sheets->sheet ?? [] as $sheet) {
            $name = trim((string) $sheet['name']) ?: ('Sheet'.$index);
            $attrs = $sheet->attributes();
            $rId = (string) ($attrs['rid'] ?? $attrs['id'] ?? '');
            if ($rId === '' && $sheet->attributes('r', true)) {
                $rId = (string) ($sheet->attributes('r', true)['id'] ?? '');
            }
            $file = $relTargets[$rId] ?? ('xl/worksheets/sheet'.$index.'.xml');
            $files[$name] = $file;
            $index++;
        }

        if ($files === []) {
            $files['Sheet1'] = 'xl/worksheets/sheet1.xml';
        }

        return $files;
    }

    /**
     * @param  array<int, string>  $sharedStrings
     * @return list<list<string>>
     */
    private static function parseSheet(string $xml, array $sharedStrings): array
    {
        $sheet = self::toSimpleXml($xml);
        $rows = [];

        foreach ($sheet->sheetData->row ?? [] as $row) {
            $cells = [];
            foreach ($row->c ?? [] as $cell) {
                $ref = (string) $cell['r'];
                $col = self::columnIndex($ref);
                $cells[$col] = self::cellValue($cell, $sharedStrings);
            }
            if ($cells === []) {
                continue;
            }
            ksort($cells);
            $max = max(array_keys($cells));
            $values = [];
            for ($i = 0; $i <= $max; $i++) {
                $values[] = $cells[$i] ?? '';
            }
            $rows[] = $values;
        }

        return $rows;
    }

    /**
     * @return array<int, string>
     */
    private static function parseSharedStrings(string $xml): array
    {
        if (trim($xml) === '') {
            return [];
        }

        $sst = self::toSimpleXml($xml);
        $strings = [];

        foreach ($sst->si ?? [] as $si) {
            $strings[] = self::sharedStringValue($si);
        }

        return $strings;
    }

    private static function sharedStringValue(\SimpleXMLElement $si): string
    {
        if (isset($si->t)) {
            return trim((string) $si->t);
        }

        $text = '';
        foreach ($si->r ?? [] as $run) {
            $text .= (string) $run->t;
        }

        return trim($text);
    }

    /**
     * @param  array<int, string>  $sharedStrings
     */
    private static function cellValue(\SimpleXMLElement $cell, array $sharedStrings): string
    {
        $type = (string) $cell['t'];

        if ($type === 's') {
            $index = (int) $cell->v;

            return $sharedStrings[$index] ?? '';
        }

        if ($type === 'inlineStr') {
            return trim((string) ($cell->is->t ?? $cell->is ?? ''));
        }

        if (isset($cell->is->t)) {
            return trim((string) $cell->is->t);
        }

        if (isset($cell->v)) {
            return trim((string) $cell->v);
        }

        return '';
    }

    private static function columnIndex(string $cellRef): int
    {
        preg_match('/^([A-Z]+)/i', $cellRef, $matches);
        $letters = strtoupper($matches[1] ?? 'A');
        $index = 0;

        foreach (str_split($letters) as $char) {
            $index = ($index * 26) + (ord($char) - 64);
        }

        return max(0, $index - 1);
    }

    /**
     * @param  list<string>  $headers
     * @param  list<list<mixed>>  $rows
     */
    private static function worksheetXml(array $headers, array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';

        $xml .= self::rowXml(1, $headers);
        foreach ($rows as $i => $row) {
            $xml .= self::rowXml($i + 2, $row);
        }

        return $xml.'</sheetData></worksheet>';
    }

    /**
     * @param  list<mixed>  $values
     */
    private static function rowXml(int $rowNumber, array $values): string
    {
        $xml = '<row r="'.$rowNumber.'">';
        foreach (array_values($values) as $col => $value) {
            $ref = self::columnLetter($col).$rowNumber;
            $xml .= '<c r="'.$ref.'" t="inlineStr"><is><t>'.self::xml((string) $value).'</t></is></c>';
        }

        return $xml.'</row>';
    }

    private static function columnLetter(int $index): string
    {
        $index++;
        $letter = '';
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)).$letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }

    private static function toSimpleXml(string $xml): \SimpleXMLElement
    {
        $xml = str_replace(['r:id=', 'r:Id='], ['rid=', 'rid='], $xml);
        $xml = preg_replace('/xmlns(:[a-z0-9]+)?="[^"]*"/i', '', $xml) ?? $xml;
        $parsed = simplexml_load_string($xml);
        if ($parsed === false) {
            throw new RuntimeException('The Excel file could not be parsed.');
        }

        return $parsed;
    }

    private static function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
