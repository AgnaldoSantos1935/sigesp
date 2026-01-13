<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Regional;
use App\Models\UnidadeEscolar;
use App\Models\Endereco;
use App\Models\Contato;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportarEscolas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sigesp:importar-escolas 
                            {file : Caminho para o arquivo CSV}
                            {--delimiter=; : Delimitador do CSV (padrão: ;)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa escolas e regionais de um arquivo CSV (convertido do Excel).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');
        $delimiter = $this->option('delimiter');

        if (!file_exists($file)) {
            $this->error("Arquivo não encontrado: $file");
            return 1;
        }

        $this->info("Iniciando importação de: $file");

        $handle = fopen($file, 'r');
        if (!$handle) {
            $this->error("Não foi possível abrir o arquivo.");
            return 1;
        }

        // Ler cabeçalho
        $header = fgetcsv($handle, 0, $delimiter);
        if (!$header) {
            $this->error("Arquivo vazio ou formato inválido.");
            fclose($handle);
            return 1;
        }

        // Normalizar cabeçalho para minúsculas e sem acentos (simplificado)
        $header = array_map(function($h) {
            return strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $h))));
        }, $header);

        $this->info("Colunas detectadas: " . implode(', ', $header));

        // Mapeamento de colunas (ajuste conforme necessário)
        $map = [
            'inep' => array_search('inep', $header),
            'nome' => array_search('nome', $header) !== false ? array_search('nome', $header) : array_search('escola', $header),
            'regional' => array_search('regional', $header) !== false ? array_search('regional', $header) : array_search('diretoria', $header),
            'municipio' => array_search('municipio', $header) !== false ? array_search('municipio', $header) : array_search('cidade', $header),
            'uf' => array_search('uf', $header) !== false ? array_search('uf', $header) : array_search('estado', $header),
            'cep' => array_search('cep', $header),
            'logradouro' => array_search('logradouro', $header) !== false ? array_search('logradouro', $header) : array_search('endereco', $header),
            'bairro' => array_search('bairro', $header),
            'numero' => array_search('numero', $header),
            'telefone' => array_search('telefone', $header),
            'email' => array_search('email', $header),
        ];

        // Verificar campos obrigatórios
        if ($map['nome'] === false) {
            $this->error("Coluna 'nome' ou 'escola' não encontrada.");
            fclose($handle);
            return 1;
        }

        $count = 0;
        $updated = 0;
        $errors = 0;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                // Preencher array associativo
                $data = [];
                foreach ($map as $key => $index) {
                    $data[$key] = ($index !== false && isset($row[$index])) ? trim($row[$index]) : null;
                }

                if (empty($data['nome'])) continue;

                // 1. Tratar Regional
                $regionalId = null;
                if (!empty($data['regional'])) {
                    $regional = Regional::firstOrCreate(
                        ['nome' => $data['regional']],
                        ['municipio' => $data['municipio']] // Pode não ser exato se a regional cobrir vários, mas serve de fallback
                    );
                    $regionalId = $regional->id;
                }

                // 2. Tratar Endereço
                $enderecoId = null;
                if (!empty($data['logradouro']) || !empty($data['cep'])) {
                    // Tenta encontrar endereço existente ou cria novo
                    // Simplificação: sempre cria novo se não tiver ID vinculado, mas aqui estamos importando.
                    // Idealmente, se a escola já existe, atualizamos o endereço dela.
                }
                
                // Lógica de update/create
                $unidade = null;
                if (!empty($data['inep'])) {
                    $unidade = UnidadeEscolar::where('inep', $data['inep'])->first();
                }

                // Preparar dados de endereço
                $enderecoData = [
                    'logradouro' => $data['logradouro'] ?? 'Não informado',
                    'municipio' => $data['municipio'] ?? 'Não informado',
                    'uf' => $data['uf'] ?? 'XX',
                    'cep' => $data['cep'] ?? '00000000',
                    'bairro' => $data['bairro'],
                    'numero' => $data['numero'],
                ];

                // Preparar dados de contato
                $contatoData = [
                    'telefone_fixo' => $data['telefone'],
                    'email_1' => $data['email'],
                ];

                if ($unidade) {
                    // Atualizar
                    if ($unidade->endereco) {
                        $unidade->endereco->update($enderecoData);
                    } else {
                        $endereco = Endereco::create($enderecoData);
                        $unidade->endereco_id = $endereco->id;
                    }

                    if ($unidade->contato) {
                        $unidade->contato->update($contatoData);
                    } else {
                        $contato = Contato::create($contatoData);
                        $unidade->contato_id = $contato->id;
                    }

                    $unidade->update([
                        'nome' => $data['nome'],
                        'regional_id' => $regionalId ?? $unidade->regional_id,
                        'municipio' => $data['municipio'] ?? $unidade->municipio,
                    ]);
                    $updated++;
                } else {
                    // Criar
                    $endereco = Endereco::create($enderecoData);
                    $contato = Contato::create($contatoData);

                    UnidadeEscolar::create([
                        'nome' => $data['nome'],
                        'inep' => $data['inep'],
                        'regional_id' => $regionalId,
                        'municipio' => $data['municipio'],
                        'endereco_id' => $endereco->id,
                        'contato_id' => $contato->id,
                    ]);
                    $count++;
                }
            }

            DB::commit();
            fclose($handle);

            $this->info("Importação concluída!");
            $this->info("Novas escolas: $count");
            $this->info("Escolas atualizadas: $updated");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->error("Erro durante a importação: " . $e->getMessage());
            Log::error($e);
            return 1;
        }

        return 0;
    }
}
