<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizOption;
use App\Support\SimpleXlsx;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class QuizMsqImporter
{
    public const TEMPLATE_HEADERS = [
        'Part',
        'Question',
        'Option A',
        'Option B',
        'Option C',
        'Option D',
        'Answer Key',
        'Marks',
    ];

    /**
     * @return array{imported: int, replaced: bool}
     */
    public function import(Quiz $quiz, UploadedFile $file, bool $replaceExisting = false): array
    {
        $rows = $this->parseFile($file);

        if ($rows === []) {
            throw new RuntimeException('No valid MSQs were found in the Excel file.');
        }

        return DB::transaction(function () use ($quiz, $rows, $replaceExisting) {
            $replaced = false;

            if ($replaceExisting && $quiz->questions()->exists()) {
                $questionIds = $quiz->questions()->pluck('id');
                QuizOption::whereIn('question_id', $questionIds)->delete();
                $quiz->questions()->delete();
                $replaced = true;
            }

            $sortOrder = (int) $quiz->questions()->max('sort_order');

            foreach ($rows as $row) {
                $sortOrder++;
                $question = $quiz->questions()->create([
                    'part' => $row['part'],
                    'question_text' => $row['question'],
                    'marks' => $row['marks'],
                    'sort_order' => $sortOrder,
                ]);

                foreach ($row['options'] as $index => $option) {
                    $question->options()->create([
                        'option_text' => $option['text'],
                        'is_correct' => $option['is_correct'],
                        'sort_order' => $index,
                    ]);
                }
            }

            return [
                'imported' => count($rows),
                'replaced' => $replaced,
            ];
        });
    }

    /**
     * @return list<array{part: ?string, question: string, marks: int, options: list<array{text: string, is_correct: bool}>}>
     */
    public function parseFile(UploadedFile $file): array
    {
        $sheets = SimpleXlsx::read($file->getRealPath());
        if ($sheets === []) {
            throw new RuntimeException('The Excel file does not contain any worksheets.');
        }

        [$questionSheetName, $questionRows] = $this->pickQuestionSheet($sheets);
        $headerMap = $this->headerMap($questionRows[0] ?? []);

        if (! isset($headerMap['question'])) {
            throw new RuntimeException('The Excel file must have a Question column.');
        }

        $answerKeys = $this->answerKeysFromSheets($sheets, $questionSheetName, $headerMap);
        $parsed = [];
        $errors = [];

        foreach (array_slice($questionRows, 1) as $offset => $row) {
            $excelRow = $offset + 2;
            $question = $this->cell($row, $headerMap['question'] ?? null);
            if ($question === '') {
                continue;
            }

            $options = $this->optionsFromRow($row, $headerMap);
            if (count($options) < 2) {
                $errors[] = 'Row '.$excelRow.': at least two options (A and B) are required.';
                continue;
            }

            $answer = $this->cell($row, $headerMap['answer'] ?? null);
            if ($answer === '' && isset($answerKeys[$offset + 1])) {
                $answer = $answerKeys[$offset + 1];
            }
            if ($answer === '' && isset($answerKeys[$excelRow])) {
                $answer = $answerKeys[$excelRow];
            }

            $correctLetters = $this->resolveAnswerLetters($answer, $options);
            if ($correctLetters === []) {
                $errors[] = 'Row '.$excelRow.': answer key is missing or does not match an option.';
                continue;
            }

            $parsed[] = [
                'part' => $this->cell($row, $headerMap['part'] ?? null) ?: null,
                'question' => $question,
                'marks' => $this->marks($this->cell($row, $headerMap['marks'] ?? null)),
                'options' => collect($options)->map(function (string $text, string $letter) use ($correctLetters) {
                    return [
                        'text' => $text,
                        'is_correct' => in_array($letter, $correctLetters, true),
                    ];
                })->values()->all(),
            ];
        }

        if ($errors !== []) {
            throw new RuntimeException(implode("\n", array_slice($errors, 0, 15)));
        }

        return $parsed;
    }

    public static function templateBinary(): string
    {
        return SimpleXlsx::build([
            'MSQs' => [
                'headers' => self::TEMPLATE_HEADERS,
                'rows' => [[
                    'Part-I',
                    'The Khyber Pakhtunkhwa Local Government Act was enacted in which year?',
                    '2010',
                    '2011',
                    '2013',
                    '2015',
                    'C',
                    '1',
                ]],
            ],
            'Answer Key' => [
                'headers' => ['Question No', 'Answer Key'],
                'rows' => [
                    ['1', 'C'],
                ],
            ],
        ]);
    }

    /**
     * @param  array<string, list<list<string>>>  $sheets
     * @return array{0: string, 1: list<list<string>>}
     */
    private function pickQuestionSheet(array $sheets): array
    {
        foreach ($sheets as $name => $rows) {
            if ($this->looksLikeAnswerSheet($name, $rows[0] ?? [])) {
                continue;
            }
            $map = $this->headerMap($rows[0] ?? []);
            if (isset($map['question'])) {
                return [$name, $rows];
            }
        }

        $firstName = array_key_first($sheets);

        return [$firstName, $sheets[$firstName]];
    }

    /**
     * @param  array<string, list<list<string>>>  $sheets
     * @param  array<string, int>  $questionHeaderMap
     * @return array<int, string>
     */
    private function answerKeysFromSheets(array $sheets, string $questionSheetName, array $questionHeaderMap): array
    {
        if (isset($questionHeaderMap['answer'])) {
            return [];
        }

        foreach ($sheets as $name => $rows) {
            if ($name === $questionSheetName) {
                continue;
            }
            if (! $this->looksLikeAnswerSheet($name, $rows[0] ?? [])) {
                $map = $this->headerMap($rows[0] ?? []);
                if (! isset($map['answer'])) {
                    continue;
                }
            }

            $map = $this->headerMap($rows[0] ?? []);
            $keys = [];
            foreach (array_slice($rows, 1) as $offset => $row) {
                $answer = $this->cell($row, $map['answer'] ?? 1);
                if ($answer === '' && isset($row[1])) {
                    $answer = trim((string) $row[1]);
                }
                if ($answer === '' && isset($row[0]) && ! is_numeric($row[0])) {
                    $answer = trim((string) $row[0]);
                }
                if ($answer === '') {
                    continue;
                }

                $number = (int) $this->cell($row, $map['number'] ?? 0);
                $keys[$number > 0 ? $number : ($offset + 1)] = $answer;
            }

            if ($keys !== []) {
                return $keys;
            }
        }

        return [];
    }

    /**
     * @param  list<string>  $headers
     */
    private function looksLikeAnswerSheet(string $name, array $headers): bool
    {
        $normalized = $this->normalize($name);
        if (str_contains($normalized, 'answer') || str_contains($normalized, 'key')) {
            return true;
        }

        $map = $this->headerMap($headers);

        return isset($map['answer']) && ! isset($map['question']);
    }

    /**
     * @param  list<string>  $headers
     * @return array<string, int>
     */
    private function headerMap(array $headers): array
    {
        $map = [];

        foreach ($headers as $index => $header) {
            $key = $this->headerKey((string) $header);
            if ($key && ! isset($map[$key])) {
                $map[$key] = (int) $index;
            }
        }

        return $map;
    }

    private function headerKey(string $header): ?string
    {
        $value = $this->normalize($header);

        $aliases = [
            'question' => ['question', 'questiontext', 'msq', 'msqs', 'mcq', 'mcqs', 'statement', 'questions'],
            'part' => ['part', 'section', 'partsection'],
            'marks' => ['marks', 'mark', 'points', 'score'],
            'answer' => ['answer', 'answerkey', 'correct', 'correctanswer', 'key', 'correctoption'],
            'number' => ['sr', 'sno', 'no', 'qno', 'questionno', 'questionnumber', 'number'],
            'option_a' => ['optiona', 'a', 'choicea', 'option1', '1'],
            'option_b' => ['optionb', 'b', 'choiceb', 'option2', '2'],
            'option_c' => ['optionc', 'c', 'choicec', 'option3', '3'],
            'option_d' => ['optiond', 'd', 'choiced', 'option4', '4'],
            'option_e' => ['optione', 'e', 'choicee', 'option5', '5'],
            'option_f' => ['optionf', 'f', 'choicef', 'option6', '6'],
        ];

        foreach ($aliases as $key => $names) {
            if (in_array($value, $names, true)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param  list<string>  $row
     * @param  array<string, int>  $headerMap
     * @return array<string, string>
     */
    private function optionsFromRow(array $row, array $headerMap): array
    {
        $options = [];
        foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $letter) {
            $text = $this->cell($row, $headerMap['option_'.strtolower($letter)] ?? null);
            if ($text !== '') {
                $options[$letter] = $text;
            }
        }

        return $options;
    }

    /**
     * @param  array<string, string>  $options
     * @return list<string>
     */
    private function resolveAnswerLetters(string $answer, array $options): array
    {
        $answer = trim($answer);
        if ($answer === '') {
            return [];
        }

        $letters = array_keys($options);
        $normalized = strtoupper(preg_replace('/option|\s+/i', '', $answer) ?? $answer);
        $normalized = str_replace([')', '(', '.', '-', '/', '&'], ',', $normalized);
        $candidates = array_values(array_filter(array_map('trim', explode(',', $normalized))));

        $found = [];
        $allAreLetters = $candidates !== [];
        foreach ($candidates as $candidate) {
            if (preg_match('/^[A-F]$/', $candidate) && isset($options[$candidate])) {
                if (! in_array($candidate, $found, true)) {
                    $found[] = $candidate;
                }
                continue;
            }
            if (preg_match('/^[A-F]{2,6}$/', $candidate)) {
                foreach (str_split($candidate) as $letter) {
                    if (isset($options[$letter]) && ! in_array($letter, $found, true)) {
                        $found[] = $letter;
                    }
                }
                continue;
            }
            $allAreLetters = false;
        }

        if ($allAreLetters && $found !== []) {
            return $found;
        }

        foreach ($options as $letter => $text) {
            if (strcasecmp($text, $answer) === 0) {
                return [$letter];
            }
        }

        if (ctype_digit($answer) && isset($letters[((int) $answer) - 1])) {
            return [$letters[((int) $answer) - 1]];
        }

        return $found;
    }

    /**
     * @param  list<string>  $row
     */
    private function cell(array $row, ?int $index): string
    {
        if ($index === null || ! array_key_exists($index, $row)) {
            return '';
        }

        return trim((string) $row[$index]);
    }

    private function marks(string $value): int
    {
        $marks = (int) $value;

        return $marks > 0 ? min($marks, 100) : 1;
    }

    private function normalize(string $value): string
    {
        return strtolower((string) preg_replace('/[^a-z0-9]+/i', '', $value));
    }
}
