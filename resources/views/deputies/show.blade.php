<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Deputado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card mb-4">
            <div class="row g-0">
                <div class="col-md-3">
                    <img src="{{ $deputy->photo_url }}" 
                         class="img-fluid rounded-start" 
                         alt="{{ $deputy->name }}">
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <h1 class="card-title">{{ $deputy->name }}</h1>
                        <p class="card-text">
                            <strong>Partido:</strong> {{ $deputy->party }}<br>
                            <strong>Estado:</strong> {{ $deputy->state }}<br>
                            <strong>Email:</strong> {{ $deputy->email ?? 'Não disponível' }}<br>
                            <strong>Total de Despesas:</strong> R$ {{ number_format($totalExpenses, 2, ',', '.') }}<br>
                            <strong>Média por Despesa:</strong> R$ {{ number_format($averageExpense, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <h2 class="mb-4">Despesas</h2>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Fornecedor</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Documento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>{{ $expense->supplier_name ?? 'Não informado' }}</td>
                        <td>R$ {{ number_format($expense->value, 2, ',', '.') }}</td>
                        <td>{{ $expense->expense_type }}</td>
                        <td>
                            @if ($expense->document_url)
                            <a href="{{ $expense->document_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                Ver
                            </a>
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $expenses->links() }}
        </div>
        
        <a href="{{ route('deputies.index') }}" class="btn btn-secondary mt-4">
            Voltar para lista de deputados
        </a>
    </div>
</body>
</html>