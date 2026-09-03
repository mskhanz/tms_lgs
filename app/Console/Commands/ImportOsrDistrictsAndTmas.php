<?php

namespace App\Console\Commands;

use App\Models\District;
use App\Models\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportOsrDistrictsAndTmas extends Command
{
    protected $signature = 'tms:import-osr-master
                            {--source=osr_expenditure : Source MySQL database name}';

    protected $description = 'Import districts and TMAs from the OSR expenditure database into TMS';

    /**
     * Known codes for KP districts (OSR district_code is usually null).
     */
    private array $districtCodes = [
        'Abbottabad' => 'ABT',
        'Bajaur' => 'BAJ',
        'Bannu' => 'BNU',
        'Battagram' => 'BTG',
        'Buner' => 'BNR',
        'Charsadda' => 'CHR',
        'Dera Ismail Khan' => 'DIK',
        'D.I. Khan' => 'DIK',
        'Hangu' => 'HNG',
        'Haripur' => 'HRP',
        'Karak' => 'KRK',
        'Khyber' => 'KHY',
        'Kohat' => 'KHT',
        'Kolai Pallas' => 'KLP',
        'Kurram' => 'KRM',
        'Lakki Marwat' => 'LKM',
        'Lower Chitral' => 'LCH',
        'Lower Dir' => 'LDR',
        'Lower Kohistan' => 'LKS',
        'Malakand' => 'MLK',
        'Mansehra' => 'MNS',
        'Mardan' => 'MRD',
        'Mohmand' => 'MHM',
        'North Waziristan' => 'NWZ',
        'Nowshera' => 'NSW',
        'Orakzai' => 'ORK',
        'Peshawar' => 'PSH',
        'Shangla' => 'SHG',
        'South Waziristan' => 'SWZ',
        'Swabi' => 'SWB',
        'Swat' => 'SWT',
        'Tank' => 'TNK',
        'Tor Ghar' => 'TGH',
        'Upper Chitral' => 'UCH',
        'Upper Dir' => 'UDR',
        'Upper Kohistan' => 'UKS',
    ];

    public function handle(): int
    {
        $source = $this->option('source');

        if (! $this->sourceDatabaseExists($source)) {
            $this->error("Source database [{$source}] was not found on this MySQL server.");

            return self::FAILURE;
        }

        $districtsImported = $this->importDistricts($source);
        $tmasImported = $this->importTmas($source);

        $this->newLine();
        $this->info("Districts created: {$districtsImported['created']}, updated: {$districtsImported['updated']}, skipped: {$districtsImported['skipped']}.");
        $this->info("TMAs created: {$tmasImported['created']}, updated: {$tmasImported['updated']}, skipped: {$tmasImported['skipped']}.");

        return self::SUCCESS;
    }

    private function sourceDatabaseExists(string $database): bool
    {
        $row = DB::selectOne(
            'SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?',
            [$database]
        );

        return (bool) $row;
    }

    private function importDistricts(string $source): array
    {
        $this->info('Importing districts...');

        $rows = DB::select("
            SELECT district_id, district_name, district_code, is_active
            FROM `{$source}`.districts
            WHERE deleted_at IS NULL
            ORDER BY district_name
        ");

        $created = $updated = $skipped = 0;

        foreach ($rows as $row) {
            $name = trim((string) $row->district_name);
            if ($name === '') {
                $skipped++;
                continue;
            }

            $canonicalName = $this->canonicalDistrictName($name);
            $district = $this->findDistrict($canonicalName) ?? $this->findDistrict($name);

            $code = $this->resolveDistrictCode($canonicalName, $name, $row->district_code, $district);

            if ($district) {
                $district->fill([
                    'code' => $district->code ?: $code,
                    'is_active' => (bool) $row->is_active,
                ]);
                if ($district->isDirty()) {
                    $district->save();
                    $updated++;
                } else {
                    $skipped++;
                }
                continue;
            }

            District::create([
                'name' => $canonicalName,
                'code' => $code,
                'is_active' => (bool) $row->is_active,
            ]);
            $created++;
        }

        return compact('created', 'updated', 'skipped');
    }

    private function importTmas(string $source): array
    {
        $this->info('Importing TMAs as organizations...');

        $lcb = Organization::where('code', 'LCB')->first();
        $parentId = $lcb?->id;

        $rows = DB::select("
            SELECT t.tma_id, t.district_id, t.tma_name, t.address, t.contact_number, t.is_active,
                   d.district_name
            FROM `{$source}`.tmas t
            INNER JOIN `{$source}`.districts d ON d.district_id = t.district_id
            WHERE t.deleted_at IS NULL
            ORDER BY t.tma_id
        ");

        $created = $updated = $skipped = 0;

        foreach ($rows as $row) {
            $tmaName = trim((string) $row->tma_name);
            if ($tmaName === '') {
                $skipped++;
                continue;
            }

            $district = $this->findDistrict($this->canonicalDistrictName($row->district_name))
                ?? $this->findDistrict(trim((string) $row->district_name));

            $payload = [
                'name' => $this->tmaOrganizationName($tmaName),
                'type' => 'tma',
                'parent_id' => $parentId,
                'district_id' => $district?->id,
                'address' => $row->address,
                'contact_number' => $row->contact_number,
                'is_active' => (bool) $row->is_active,
            ];

            $organization = Organization::withTrashed()->where('code', $this->tmaCode((int) $row->tma_id))->first();

            if ($organization) {
                if ($organization->trashed()) {
                    $organization->restore();
                }
                $organization->fill($payload);
                if ($organization->isDirty()) {
                    $organization->save();
                    $updated++;
                } else {
                    $skipped++;
                }
                continue;
            }

            Organization::create(array_merge($payload, [
                'code' => $this->tmaCode((int) $row->tma_id),
            ]));
            $created++;
        }

        return compact('created', 'updated', 'skipped');
    }

    private function findDistrict(string $name): ?District
    {
        $normalized = $this->normalizeDistrictName($name);

        return District::withTrashed()->get()->first(function (District $district) use ($normalized) {
            return $this->normalizeDistrictName($district->name) === $normalized;
        });
    }

    private function canonicalDistrictName(string $name): string
    {
        $displayAliases = [
            'dera ismail khan' => 'Dera Ismail Khan',
        ];

        $normalized = $this->normalizeDistrictName($name);

        return $displayAliases[$normalized] ?? trim($name);
    }

    private function normalizeDistrictName(string $name): string
    {
        $name = strtolower(trim($name));
        $name = str_replace(['.', '-'], ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name) ?? $name;

        $aliases = [
            'd i khan' => 'dera ismail khan',
            'di khan' => 'dera ismail khan',
            'torghar' => 'tor ghar',
            'kolai palas' => 'kolai pallas',
        ];

        return $aliases[$name] ?? $name;
    }

    private function resolveDistrictCode(string $canonicalName, string $sourceName, ?string $osrCode, ?District $existing): string
    {
        if ($existing && $existing->code) {
            return $existing->code;
        }

        if ($osrCode) {
            return Str::upper(Str::limit($osrCode, 20, ''));
        }

        $code = $this->districtCodes[$canonicalName]
            ?? $this->districtCodes[$sourceName]
            ?? Str::upper(Str::substr(preg_replace('/[^A-Za-z]/', '', $canonicalName) ?: 'DST', 0, 3));

        $base = $code;
        $i = 2;
        while (District::withTrashed()->where('code', $code)->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))->exists()) {
            $code = Str::upper(Str::substr($base, 0, 17)) . $i;
            $i++;
        }

        return $code;
    }

    private function tmaOrganizationName(string $tmaName): string
    {
        if (Str::startsWith(Str::lower($tmaName), 'tma ')) {
            return $tmaName;
        }

        return 'TMA ' . $tmaName;
    }

    private function tmaCode(int $tmaId): string
    {
        return 'TMA-' . str_pad((string) $tmaId, 3, '0', STR_PAD_LEFT);
    }
}
