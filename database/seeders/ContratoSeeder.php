<?php

namespace Database\Seeders;

use App\Models\Contrato;
use App\Models\InstrumentoJuridico;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class ContratoSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-instituicao')->first();
        if (!$tenant) {
            return;
        }

        // 1. Aquisição de Bens (Linked to ARP)
        $arp = InstrumentoJuridico::where('numero', 'ARP-001/2025')->where('tenant_id', $tenant->id)->first();
        if ($arp) {
            Contrato::firstOrCreate(
                ['numero' => '001/2025', 'tenant_id' => $tenant->id],
                [
                    'instrumento_juridico_id' => $arp->id,
                    'ano' => 2025,
                    'objeto' => 'Aquisição de Notebooks e Monitores Dell',
                    'fornecedor_nome' => 'Dell Computadores Ltda',
                    'fornecedor_cnpj' => '00.000.000/0001-01',
                    'tipo_contrato' => Contrato::TIPO_AQUISICAO,
                    'status' => Contrato::STATUS_ATIVO,
                    'tenant_id' => $tenant->id
                ]
            );
        }

        // 2. Internet (Linked to Edital)
        $instInternet = InstrumentoJuridico::where('numero', 'EDITAL-005/2025')->where('tenant_id', $tenant->id)->first();
        if ($instInternet) {
            Contrato::firstOrCreate(
                ['numero' => '005/2025', 'tenant_id' => $tenant->id],
                [
                    'instrumento_juridico_id' => $instInternet->id,
                    'ano' => 2025,
                    'objeto' => 'Prestação de Serviço de Internet Dedicada 1Gbps',
                    'fornecedor_nome' => 'Claro Telecom S.A.',
                    'fornecedor_cnpj' => '11.111.111/0001-11',
                    'tipo_contrato' => Contrato::TIPO_INTERNET,
                    'status' => Contrato::STATUS_ATIVO,
                    'tenant_id' => $tenant->id
                ]
            );
        }

        // 3. Fábrica de Software (Linked to Dispensa)
        $instSoft = InstrumentoJuridico::where('numero', 'DISP-020/2025')->where('tenant_id', $tenant->id)->first();
        if ($instSoft) {
            Contrato::firstOrCreate(
                ['numero' => '020/2025', 'tenant_id' => $tenant->id],
                [
                    'instrumento_juridico_id' => $instSoft->id,
                    'ano' => 2025,
                    'objeto' => 'Fábrica de Software Ágil - Desenvolvimento e Manutenção',
                    'fornecedor_nome' => 'SoftHouse Tech',
                    'fornecedor_cnpj' => '33.333.333/0001-33',
                    'tipo_contrato' => Contrato::TIPO_FABRICA_SOFTWARE,
                    'status' => Contrato::STATUS_ATIVO,
                    'tenant_id' => $tenant->id
                ]
            );
        }
    }
}
