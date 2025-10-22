<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\DataTransferObjects\DeputyData;
use App\DataTransferObjects\ExpenseData;

class CamaraService
{
    private const BASE_URL = 'https://dadosabertos.camara.leg.br/api/v2';
    private const TIMEOUT = 30;
    private const RETRY_TIMES = 3;
    private const RETRY_SLEEP = 1000;

    public function getDeputies(int $perPage = 100): array
    {
        $response = Http::timeout(self::TIMEOUT)
            ->retry(self::RETRY_TIMES, self::RETRY_SLEEP)
            ->get(self::BASE_URL.'/deputados', [
                'ordem' => 'ASC',
                'ordenarPor' => 'nome',
                'itens' => $perPage
            ]);

        if ($response->failed()) {
            Log::error('Failed to fetch deputies', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [];
        }

        $data = $response->json()['dados'] ?? [];
        
        return array_map(
            fn($deputy) => new DeputyData(
                external_id: $deputy['id'],
                name: $deputy['nome'],
                party: $deputy['siglaPartido'],
                state: $deputy['siglaUf'],
                photo_url: $deputy['urlFoto'],
                email: $deputy['email'] ?? null,
                legislature_id: $deputy['idLegislatura']
            ),
            $data
        );
    }

    public function getDeputyExpenses(int $deputyId, int $perPage = 1000): array
    {
        $response = Http::timeout(self::TIMEOUT)
            ->retry(self::RETRY_TIMES, self::RETRY_SLEEP)
            ->get(self::BASE_URL."/deputados/{$deputyId}/despesas", [
                'ordem' => 'ASC',
                'ordenarPor' => 'dataDocumento',
                'itens' => $perPage
            ]);

        if ($response->failed()) {
            Log::error("Failed to fetch expenses for deputy {$deputyId}", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [];
        }

        $expensesData = $response->json()['dados'] ?? [];
        
        return array_map(
            fn($expense) => new ExpenseData(
                external_id: $expense['codDocumento'] ?? null,
                deputy_external_id: $deputyId,
                value: $expense['valorDocumento'],
                date: $expense['dataDocumento'],
                description: $expense['tipoDespesa'] ?? $expense['descricao'] ?? 'Despesa não especificada',
                document_url: $expense['urlDocumento'] ?? null,
                supplier_name: $expense['nomeFornecedor'] ?? null,
                supplier_document: $expense['cnpjCpfFornecedor'] ?? null,
                expense_type: $expense['tipoDespesa'] ?? 'OUTROS',
                year: $expense['ano'],
                month: $expense['mes']
            ),
            $expensesData
        );
    }
}