<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Estágios</title>
</head>

<body>
    <div class="register-container">
        <h1>Criar Conta</h1>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>Nome Completo *</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>E-mail *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Senha *</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirmar Senha *</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label>Tipo de Conta *</label>
                <select name="role" id="role" required>
                    <option value="">Selecione</option>
                    <option value="aluno">Aluno</option>
                    <option value="empresa">Empresa</option>
                </select>
            </div>

            <!-- Campos para Aluno -->
            <div id="aluno-fields" class="role-fields">
                <div class="form-group">
                    <label>Matrícula *</label>
                    <input type="text" name="matricula" value="{{ old('matricula') }}">
                </div>
                <div class="form-group">
                    <label>CPF *</label>
                    <input type="text" name="cpf" value="{{ old('cpf') }}" placeholder="00000000000">
                </div>
                <div class="form-group">
                    <label>Curso *</label>
                    <select name="curso_id">
                        <option value="">Selecione um curso</option>
                        @foreach ($cursos ?? [] as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Campos para Empresa -->
            <div id="empresa-fields" class="role-fields">
                <div class="form-group">
                    <label>Razão Social *</label>
                    <input type="text" name="razao_social" value="{{ old('razao_social') }}">
                </div>
                <div class="form-group">
                    <label>CNPJ *</label>
                    <input type="text" name="cnpj" value="{{ old('cnpj') }}" placeholder="00000000000000">
                </div>
            </div>

            <button type="submit">Cadastrar</button>

            <div class="links">
                <a href="{{ route('login') }}">Já tem conta? Faça login</a>
            </div>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const alunoFields = document.getElementById('aluno-fields');
        const empresaFields = document.getElementById('empresa-fields');

        roleSelect.addEventListener('change', function() {
            alunoFields.classList.remove('active');
            empresaFields.classList.remove('active');

            if (this.value === 'aluno') {
                alunoFields.classList.add('active');
            } else if (this.value === 'empresa') {
                empresaFields.classList.add('active');
            }
        });
    </script>
</body>

</html>
