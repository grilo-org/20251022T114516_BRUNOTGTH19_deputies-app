<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deputados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Deputados Federais</h1>
        
        <form action="{{ route('deputies.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Buscar por nome, partido ou estado..." 
                       value="{{ $search }}">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>
        
        @if($deputies->isEmpty())
            <div class="alert alert-warning">
                Nenhum deputado encontrado. Tente outra busca.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Partido</th>
                            <th>Estado</th>
                            <th>Despesas</th>
                            <th>Total Gasto</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deputies as $deputy)
                        <tr>
                            <td>{{ $deputy->name }}</td>
                            <td>{{ $deputy->party }}</td>
                            <td>{{ $deputy->state }}</td>
                            <td>{{ $deputy->expenses_count }}</td>
                            <td>R$ {{ number_format($deputy->expenses_sum_value, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('deputies.show', $deputy->id) }}" 
                                   class="btn btn-sm btn-info">
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $deputies->links() }}
            </div>
        @endif
    </div>
</body>
</html>