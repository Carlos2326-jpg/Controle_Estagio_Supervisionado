<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Sistema de Estágios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f8;
            overflow-x: hidden;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f2b4d 0%, #1a1a3e 100%);
            height: 100vh;
            position: fixed;
            color: white;
            padding: 1.8rem 1.2rem;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 8px 0 25px -10px rgba(0, 0, 0, 0.2);
        }

        .sidebar .logo-area {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar .logo-area h2 {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #c3b8ff);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .sidebar .logo-area i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #a78bfa;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 12px 16px;
            margin: 6px 0;
            border-radius: 14px;
            transition: all 0.25s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .sidebar nav a i {
            width: 24px;
            font-size: 1.2rem;
        }

        .sidebar nav a:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            transform: translateX(6px);
        }

        .sidebar nav a.active {
            background: linear-gradient(95deg, rgba(139, 92, 246, 0.4), rgba(79, 70, 229, 0.3));
            color: white;
            border-left: 3px solid #a78bfa;
        }

        .main {
            margin-left: 280px;
            padding: 1.5rem 2rem;
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 1rem 1.8rem;
            border-radius: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .header h1 {
            font-size: 1.55rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e293b, #334155);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .logout-btn {
            background: linear-gradient(105deg, #ef4444, #dc2626);
            color: white;
            border: none;
            padding: 0.6rem 1.4rem;
            border-radius: 40px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1.5rem;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-card h3 {
            font-size: 2.3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .stat-card p {
            color: #5b6e8c;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .welcome-card {
            background: linear-gradient(135deg, #1e2b5c 0%, #2a235e 100%);
            color: white;
            border: none;
        }

        .welcome-card h2 {
            color: white;
        }

        .content-area {
            display: none;
            animation: fadeInUp 0.35s ease;
        }

        .content-area.active {
            display: block;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        th {
            text-align: left;
            padding: 1rem 0.8rem;
            background: #f8fafc;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 1.5px solid #e2e8f0;
        }

        td {
            padding: 0.9rem 0.8rem;
            border-bottom: 1px solid #f0f2f5;
            color: #334155;
        }

        tr:hover td {
            background-color: #fafbff;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.7rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-success {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-warning {
            background: #fed7aa;
            color: #9a3412;
        }

        .btn {
            padding: 0.45rem 1rem;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .action-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .grid-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 1rem;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 1.8rem;
            border-radius: 2rem;
            width: 520px;
            max-width: 92%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.4rem;
            padding-bottom: 0.6rem;
            border-bottom: 2px solid #eef2ff;
        }

        .close-modal {
            cursor: pointer;
            font-size: 1.8rem;
            font-weight: 300;
        }

        .close-modal:hover {
            color: #ef4444;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            font-size: 0.8rem;
            color: #1e293b;
        }

        .form-group label .required {
            color: #ef4444;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 1rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: block;
        }

        .help-text {
            color: #6b7280;
            font-size: 0.7rem;
            margin-top: 0.25rem;
            display: block;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="logo-area">
            <i class="fas fa-graduation-cap"></i>
            <h2>Sistema de Estágios</h2>
            <p style="font-size: 0.7rem; opacity:0.7;">Administração</p>
        </div>
        <nav>
            <a href="#" onclick="showContent('dashboard'); return false;" class="nav-link active"
                data-section="dashboard">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="#" onclick="showContent('instituicoes'); return false;" class="nav-link"
                data-section="instituicoes">
                <i class="fas fa-building"></i> Instituições
            </a>
            <a href="#" onclick="showContent('cursos'); return false;" class="nav-link" data-section="cursos">
                <i class="fas fa-book-open"></i> Cursos
            </a>
            <a href="#" onclick="showContent('coordenadores'); return false;" class="nav-link"
                data-section="coordenadores">
                <i class="fas fa-chalkboard-user"></i> Coordenadores
            </a>
            <a href="#" onclick="showContent('relatorios'); return false;" class="nav-link"
                data-section="relatorios">
                <i class="fas fa-chart-line"></i> Relatórios
            </a>
        </nav>
    </div>

    <div class="main">
        <div class="header">
            <h1><i class="fas fa-user-shield" style="margin-right: 10px; color:#4f46e5;"></i>Painel Administrativo</h1>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Sair</button>
            </form>
        </div>

        <div id="content-dashboard" class="content-area active">
            <div class="stats">
                <div class="stat-card">
                    <h3 id="total-instituicoes">0</h3>
                    <p><i class="fas fa-building"></i> Instituições</p>
                </div>
                <div class="stat-card">
                    <h3 id="total-cursos">0</h3>
                    <p><i class="fas fa-graduation-cap"></i> Cursos Ativos</p>
                </div>
                <div class="stat-card">
                    <h3 id="total-coordenadores">0</h3>
                    <p><i class="fas fa-users"></i> Coordenadores</p>
                </div>
                <div class="stat-card">
                    <h3 id="total-alunos">0</h3>
                    <p><i class="fas fa-user-graduate"></i> Alunos</p>
                </div>
            </div>
            <div class="card welcome-card">
                <h2><i class="fas fa-smile-wink"></i> Bem-vindo, {{ auth()->user()->name ?? 'Administrador' }}!</h2>
                <p style="opacity:0.9;">✨ Gerencie instituições, cursos e coordenadores com total controle. Utilize o
                    menu lateral para navegar.</p>
            </div>
        </div>

        <div id="content-instituicoes" class="content-area">
            <div class="card">
                <div
                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap:wrap; gap:12px; margin-bottom: 1.2rem;">
                    <h2><i class="fas fa-building"></i> Instituições de Ensino</h2>
                    <button class="btn btn-primary" onclick="openModal('instituicao')"><i class="fas fa-plus"></i> Nova
                        Instituição</button>
                </div>
                <div class="table-wrapper">
                    <table id="instituicoes-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Sigla</th>
                                <th>Cidade/UF</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="instituicoes-tbody">
                            <tr>
                                <td colspan="6" style="text-align:center;">Carregando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="content-cursos" class="content-area">
            <div class="card">
                <div
                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap:wrap; gap:12px;">
                    <h2><i class="fas fa-book"></i> Cursos</h2>
                    <button class="btn btn-primary" onclick="openModal('curso')"><i class="fas fa-plus"></i> Novo
                        Curso</button>
                </div>
                <div class="table-wrapper">
                    <table id="cursos-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Código</th>
                                <th>Modalidade</th>
                                <th>Carga Horária</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="cursos-tbody">
                            <tr>
                                <td colspan="7" style="text-align:center;">Carregando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="content-coordenadores" class="content-area">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap:wrap;">
                    <h2><i class="fas fa-chalkboard-user"></i> Coordenadores</h2>
                    <button class="btn btn-primary" onclick="openModal('coordenador')"><i
                            class="fas fa-user-plus"></i> Novo Coordenador</button>
                </div>
                <div class="table-wrapper">
                    <table id="coordenadores-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Curso</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="coordenadores-tbody">
                            <tr>
                                <td colspan="6" style="text-align:center;">Carregando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="content-relatorios" class="content-area">
            <div class="card">
                <h2><i class="fas fa-chart-simple"></i> Central de Relatórios</h2>
                <div class="grid-buttons">
                    <button class="btn btn-primary" onclick="gerarRelatorio('instituicoes')"><i
                            class="fas fa-file-alt"></i> Instituições</button>
                    <button class="btn btn-primary" onclick="gerarRelatorio('cursos')"><i
                            class="fas fa-file-alt"></i> Cursos</button>
                    <button class="btn btn-primary" onclick="gerarRelatorio('coordenadores')"><i
                            class="fas fa-file-alt"></i> Coordenadores</button>
                    <button class="btn btn-primary" onclick="gerarRelatorio('alunos')"><i
                            class="fas fa-file-alt"></i> Alunos</button>
                    <button class="btn btn-primary" onclick="gerarRelatorio('estagios')"><i
                            class="fas fa-briefcase"></i> Estágios</button>
                    <button class="btn btn-success" onclick="exportarCSV()"><i class="fas fa-download"></i> Exportar
                        CSV</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Título</h3><span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <div id="modal-body"></div>
        </div>
    </div>

    <script>
        // Helper para obter token CSRF
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        // Máscara para CNPJ
        function mascaraCNPJ(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
                input.value = value;
            }
        }

        async function fetchAPI(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            };
            const response = await fetch(url, {
                ...defaultOptions,
                ...options
            });
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({
                    message: response.statusText
                }));
                console.error('API Error:', errorData);
                const errorMessage = errorData.errors ? Object.values(errorData.errors).flat().join(', ') : errorData
                    .message;
                throw new Error(errorMessage || `HTTP ${response.status}`);
            }
            return response.json();
        }

        async function carregarDashboard() {
            try {
                const [inst, cursos, coord, alunos] = await Promise.all([
                    fetchAPI('/api/instituicoes/count').catch(() => ({
                        total: 0
                    })),
                    fetchAPI('/api/cursos/count').catch(() => ({
                        total: 0
                    })),
                    fetchAPI('/api/coordenadores/count').catch(() => ({
                        total: 0
                    })),
                    fetchAPI('/api/alunos/count').catch(() => ({
                        total: 0
                    }))
                ]);
                document.getElementById('total-instituicoes').innerText = inst.total || 0;
                document.getElementById('total-cursos').innerText = cursos.total || 0;
                document.getElementById('total-coordenadores').innerText = coord.total || 0;
                document.getElementById('total-alunos').innerText = alunos.total || 0;
            } catch (e) {
                console.warn('Erro ao carregar dashboard:', e);
            }
        }

        async function carregarInstituicoes() {
            try {
                const data = await fetchAPI('/instituicoes');
                const tbody = document.getElementById('instituicoes-tbody');
                if (data.data && data.data.length) {
                    tbody.innerHTML = data.data.map(i => `
                <tr>
                    <td>${i.id}</td>
                    <td>${i.nome_instituicao}</td>
                    <td>${i.sigla || '-'}</td>
                    <td>${i.cidade}/${i.estado}</td>
                    <td><span class="badge ${i.ativa ? 'badge-success' : 'badge-danger'}">${i.ativa ? 'Ativa' : 'Inativa'}</span></td>
                    <td class="action-group">
                        <button class="btn btn-warning" onclick="editarInstituicao(${i.id})"><i class="fas fa-edit"></i> Editar</button>
                        <button class="btn btn-danger" onclick="toggleInstituicao(${i.id})"><i class="fas fa-sync-alt"></i> ${i.ativa ? 'Desativar' : 'Ativar'}</button>
                    </td>
                </tr>
            `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="6">Nenhuma instituição cadastrada</td></tr>';
                }
            } catch (e) {
                document.getElementById('instituicoes-tbody').innerHTML =
                    '<tr><td colspan="6">Erro ao carregar instituições: ' + e.message + '</td></tr>';
                console.error(e);
            }
        }

        // Função para editar instituição
        async function editarInstituicao(id) {
            try {
                const instituicao = await fetchAPI(`/instituicoes/${id}`);

                const modal = document.getElementById('modal');
                const modalTitle = document.getElementById('modal-title');
                const modalBody = document.getElementById('modal-body');

                modalTitle.innerText = '✏️ Editar Instituição';
                modalBody.innerHTML = `
            <form id="dynamicForm" onsubmit="submitEditarInstituicao(event, ${id})">
                <div class="form-group">
                    <label>Nome <span class="required">*</span></label>
                    <input name="nome_instituicao" value="${instituicao.nome_instituicao}" required>
                </div>
                <div class="form-group">
                    <label>Sigla <span class="required">*</span></label>
                    <input name="sigla" value="${instituicao.sigla}" required>
                </div>
                <div class="form-group">
                    <label>CNPJ <span class="required">*</span></label>
                    <input name="cnpj" id="cnpjInput" value="${instituicao.cnpj}" placeholder="00.000.000/0000-00" required>
                    <small class="help-text">Digite apenas números ou use o formato 00.000.000/0000-00</small>
                </div>
                <div class="form-group">
                    <label>Endereço <span class="required">*</span></label>
                    <input name="endereco" value="${instituicao.endereco}" required>
                </div>
                <div class="form-group">
                    <label>Cidade <span class="required">*</span></label>
                    <input name="cidade" value="${instituicao.cidade}" required>
                </div>
                <div class="form-group">
                    <label>Estado (UF) <span class="required">*</span></label>
                    <input name="estado" maxlength="2" value="${instituicao.estado}" required>
                </div>
                <div class="form-group">
                    <label>Telefone</label>
                    <input name="telefone" value="${instituicao.telefone || ''}">
                </div>
                <div class="form-group">
                    <label>E-mail de Contato</label>
                    <input name="email_contato" type="email" value="${instituicao.email_contato || ''}">
                </div>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>
        `;

                const cnpjInput = document.getElementById('cnpjInput');
                if (cnpjInput) {
                    cnpjInput.addEventListener('input', function() {
                        mascaraCNPJ(this);
                    });
                    mascaraCNPJ(cnpjInput);
                }

                modal.style.display = 'flex';
            } catch (e) {
                alert('Erro ao carregar dados da instituição: ' + e.message);
            }
        }

        // Função para enviar edição
        async function submitEditarInstituicao(event, id) {
            event.preventDefault();
            const formData = new FormData(event.target);
            let data = Object.fromEntries(formData.entries());

            if (data.cnpj) {
                // Remove tudo que não for número
                data.cnpj = data.cnpj.replace(/[^\d]/g, '');

                if (data.cnpj.length !== 14) {
                    alert('❌ CNPJ deve conter 14 dígitos. Você informou ' + data.cnpj.length + ' dígitos.');
                    return;
                }

                // Verificação básica de CNPJ (evita números repetidos)
                const firstDigit = data.cnpj[0];
                if (data.cnpj.split('').every(d => d === firstDigit)) {
                    alert('❌ CNPJ inválido. Todos os dígitos são iguais.');
                    return;
                }
            }

            try {
                const response = await fetch(`/instituicoes/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const error = await response.json();
                    console.error('API Error:', error);
                    const errorMsg = error.errors ? Object.values(error.errors).flat().join('\n') : error.message;
                    alert('❌ Erro ao atualizar: ' + errorMsg);
                    return;
                }

                closeModal();
                carregarInstituicoes();
                carregarDashboard();
                alert('✅ Instituição atualizada com sucesso!');
            } catch (e) {
                alert('❌ Erro ao atualizar instituição: ' + e.message);
            }
        }

        // Função para alternar status (ativar/desativar)
        // Função para alternar status (ativar/desativar)
        async function toggleInstituicao(id) {
            const acao = document.querySelector(`button[onclick="toggleInstituicao(${id})"]`).innerText.includes(
                'Desativar') ? 'desativar' : 'ativar';

            if (!confirm(`Tem certeza que deseja ${acao} esta instituição?`)) {
                return;
            }

            try {
                const response = await fetch(`/instituicoes/${id}/toggle-ativa`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    const error = await response.json();
                    console.warn('Aviso:', error.message);
                    // Mostra apenas um aviso, não bloqueia
                    alert('⚠️ ' + error.message);
                }

                // Recarrega a lista mesmo se houver aviso
                carregarInstituicoes();
                carregarDashboard();

            } catch (e) {
                console.error('Erro:', e);
                // Mesmo com erro, tenta recarregar
                carregarInstituicoes();
                carregarDashboard();
                alert('⚠️ Status alterado, mas pode haver vínculos ativos.');
            }
        }

        // Função para carregar cursos com ordenação correta
        async function carregarCursos() {
            try {
                const data = await fetchAPI('/cursos');
                const tbody = document.getElementById('cursos-tbody');
                if (data.data && data.data.length) {
                    // Ordena os cursos por ID antes de exibir
                    const cursosOrdenados = [...data.data].sort((a, b) => a.id - b.id);

                    tbody.innerHTML = cursosOrdenados.map(c => `
                <tr>
                    <td>${c.id}</span></td>
                    <td>${c.nome}</span></td>
                    <td>${c.codigo}</span></span></span></td>
                    <td>${c.modalidade}</span></span></td>
                    <td>${c.carga_horaria_estagio}h</span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></td>
                    <td><span class="badge ${c.ativo ? 'badge-success' : 'badge-danger'}">${c.ativo ? 'Ativo' : 'Inativo'}</span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></span></td>
                    <td class="action-group">
                        <button class="btn btn-warning" onclick="editarCurso(${c.id})"><i class="fas fa-edit"></i> Editar</button>
                        <button class="btn btn-danger" onclick="toggleCurso(${c.id}, ${c.ativo})"><i class="fas fa-power-off"></i> ${c.ativo ? 'Desativar' : 'Ativar'}</button>
                    </span></span></span></span></span></span></span></span></span>}
                </tr>
            `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="7">Nenhum curso cadastrado</span></span></td></tr>';
                }
            } catch (e) {
                document.getElementById('cursos-tbody').innerHTML =
                    `<tr><td colspan="7">Erro ao carregar cursos: ${e.message}</span></span></td></tr>`;
                console.error(e);
            }
        }

        // Função para editar curso
        async function editarCurso(id) {
            try {
                const curso = await fetchAPI(`/cursos/${id}`);

                const modal = document.getElementById('modal');
                const modalTitle = document.getElementById('modal-title');
                const modalBody = document.getElementById('modal-body');

                modalTitle.innerText = '✏️ Editar Curso';
                modalBody.innerHTML = `
            <form id="dynamicForm" onsubmit="submitEditarCurso(event, ${id})">
                <div class="form-group">
                    <label>Nome <span class="required">*</span></label>
                    <input name="nome" value="${curso.nome.replace(/"/g, '&quot;')}" required>
                </div>
                <div class="form-group">
                    <label>Código <span class="required">*</span></label>
                    <input name="codigo" value="${curso.codigo}" required>
                </div>
                <div class="form-group">
                    <label>Carga Horária Estágio <span class="required">*</span></label>
                    <input name="carga_horaria_estagio" type="number" value="${curso.carga_horaria_estagio}" required>
                </div>
                <div class="form-group">
                    <label>Modalidade <span class="required">*</span></label>
                    <select name="modalidade" required>
                        <option value="Presencial" ${curso.modalidade === 'Presencial' ? 'selected' : ''}>Presencial</option>
                        <option value="EAD" ${curso.modalidade === 'EAD' ? 'selected' : ''}>EAD</option>
                        <option value="Hibrido" ${curso.modalidade === 'Hibrido' ? 'selected' : ''}>Híbrido</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>
        `;

                modal.style.display = 'flex';
            } catch (e) {
                alert('Erro ao carregar dados do curso: ' + e.message);
            }
        }

        // Função para enviar edição de curso
        async function submitEditarCurso(event, id) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());

            // Converte carga_horaria_estagio para número
            if (data.carga_horaria_estagio) {
                data.carga_horaria_estagio = parseInt(data.carga_horaria_estagio);
            }

            try {
                const response = await fetch(`/cursos/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const error = await response.json();
                    const errorMsg = error.errors ? Object.values(error.errors).flat().join('\n') : error.message;
                    alert('❌ Erro ao atualizar: ' + errorMsg);
                    return;
                }

                closeModal();
                carregarCursos();
                carregarDashboard();
                alert('✅ Curso atualizado com sucesso!');
            } catch (e) {
                alert('❌ Erro ao atualizar curso: ' + e.message);
            }
        }

        // Função para alternar status do curso
        async function toggleCurso(id, statusAtual) {
            const acao = statusAtual ? 'desativar' : 'ativar';

            if (!confirm(`Tem certeza que deseja ${acao} este curso?`)) {
                return;
            }

            try {
                const response = await fetch(`/cursos/${id}/inativar`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    const error = await response.json();
                    alert('❌ Erro ao alterar status: ' + (error.message || 'Erro desconhecido'));
                    return;
                }

                // Recarrega a lista de cursos
                await carregarCursos();
                await carregarDashboard();
                alert(`✅ Curso ${acao}do com sucesso!`);
            } catch (e) {
                alert('❌ Erro ao alterar status: ' + e.message);
            }
        }

        // Função para criar novo curso
        async function submitCurso(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());

            // Converte carga_horaria_estagio para número
            if (data.carga_horaria_estagio) {
                data.carga_horaria_estagio = parseInt(data.carga_horaria_estagio);
            }

            try {
                await fetchAPI('/cursos', {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                closeModal();
                carregarCursos();
                carregarDashboard();
                alert('✅ Curso criado com sucesso!');
            } catch (e) {
                alert('❌ Erro ao criar curso: ' + e.message);
            }
        }

        // Função para carregar coordenadores
        async function carregarCoordenadores() {
            try {
                const data = await fetchAPI('/coordenadores');
                const tbody = document.getElementById('coordenadores-tbody');
                if (data.data && data.data.length) {
                    tbody.innerHTML = data.data.map(coord => `
                <tr>
                    <td>${coord.id}</span></td>
                    <td>${coord.user?.name || '-'}</span></td>
                    <td>${coord.user?.email || '-'}</span></td>
                    <td>${coord.curso?.nome || '-'}</span></td>
                    <td><span class="badge ${coord.status === 'ativo' ? 'badge-success' : 'badge-warning'}">${coord.status || 'inativo'}</span></span></td>
                    <td class="action-group">
                        <button class="btn btn-warning" onclick="editarCoordenador(${coord.id})"><i class="fas fa-edit"></i> Editar</button>
                        <button class="btn btn-danger" onclick="toggleCoordenador(${coord.id}, '${coord.status}')"><i class="fas fa-ban"></i> ${coord.status === 'ativo' ? 'Inativar' : 'Ativar'}</button>
                    </span></span></span>}
                </tr>
            `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="6">Nenhum coordenador cadastrado</span></span></td></tr>';
                }
            } catch (e) {
                document.getElementById('coordenadores-tbody').innerHTML =
                    `<tr><td colspan="6">Erro ao carregar coordenadores: ${e.message}</span></span></td></tr>`;
                console.error(e);
            }
        }

        // Função para editar coordenador
        async function editarCoordenador(id) {
            try {
                const coordenador = await fetchAPI(`/coordenadores/${id}`);

                const modal = document.getElementById('modal');
                const modalTitle = document.getElementById('modal-title');
                const modalBody = document.getElementById('modal-body');

                modalTitle.innerText = '✏️ Editar Coordenador';
                modalBody.innerHTML = `
            <form id="dynamicForm" onsubmit="submitEditarCoordenador(event, ${id})">
                <div class="form-group">
                    <label>Nome <span class="required">*</span></label>
                    <input name="nome" value="${coordenador.user?.name || ''}" required>
                </div>
                <div class="form-group">
                    <label>E-mail <span class="required">*</span></label>
                    <input name="email" type="email" value="${coordenador.user?.email || ''}" required>
                </div>
                <div class="form-group">
                    <label>Telefone</label>
                    <input name="telefone" value="${coordenador.telefone || ''}">
                </div>
                <div class="form-group">
                    <label>Curso</label>
                    <select name="curso_id" required>
                        <option value="">Selecione um curso...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="ativo" ${coordenador.status === 'ativo' ? 'selected' : ''}>Ativo</option>
                        <option value="inativo" ${coordenador.status === 'inativo' ? 'selected' : ''}>Inativo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>
        `;

                // Carregar opções de cursos
                const cursosData = await fetchAPI('/cursos');
                const select = document.querySelector('select[name="curso_id"]');
                if (select && cursosData.data) {
                    select.innerHTML = '<option value="">Selecione um curso...</option>' +
                        cursosData.data.map(c =>
                            `<option value="${c.id}" ${coordenador.curso_id === c.id ? 'selected' : ''}>${c.nome}</option>`
                            ).join('');
                }

                modal.style.display = 'flex';
            } catch (e) {
                alert('Erro ao carregar dados do coordenador: ' + e.message);
            }
        }

        // Função para enviar edição de coordenador
        async function submitEditarCoordenador(event, id) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(`/coordenadores/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const error = await response.json();
                    const errorMsg = error.errors ? Object.values(error.errors).flat().join('\n') : error.message;
                    alert('❌ Erro ao atualizar: ' + errorMsg);
                    return;
                }

                closeModal();
                carregarCoordenadores();
                carregarDashboard();
                alert('✅ Coordenador atualizado com sucesso!');
            } catch (e) {
                alert('❌ Erro ao atualizar coordenador: ' + e.message);
            }
        }

        // Função para alternar status do coordenador
        async function toggleCoordenador(id, statusAtual) {
            const acao = statusAtual === 'ativo' ? 'inativar' : 'ativar';

            if (!confirm(`Tem certeza que deseja ${acao} este coordenador?`)) {
                return;
            }

            try {
                const response = await fetch(`/coordenadores/${id}/inativar`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    const error = await response.json();
                    alert('❌ Erro ao alterar status: ' + (error.message || 'Erro desconhecido'));
                    return;
                }

                await carregarCoordenadores();
                await carregarDashboard();
                alert(`✅ Coordenador ${acao}do com sucesso!`);
            } catch (e) {
                alert('❌ Erro ao alterar status: ' + e.message);
            }
        }

        // Função para alternar status do coordenador
        async function toggleCoordenador(id, statusAtual) {
            const acao = statusAtual === 'ativo' ? 'inativar' : 'ativar';

            if (!confirm(`Tem certeza que deseja ${acao} este coordenador?`)) {
                return;
            }

            try {
                const response = await fetch(`/coordenadores/${id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    const error = await response.json();
                    alert('❌ Erro ao alterar status: ' + (error.message || 'Erro desconhecido'));
                    return;
                }

                await carregarCoordenadores();
                await carregarDashboard();
                alert(`✅ Coordenador ${acao}do com sucesso!`);
            } catch (e) {
                alert('❌ Erro ao alterar status: ' + e.message);
            }
        }

        // Função para criar novo coordenador
        async function submitCoordenador(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());

            // Garantir que a senha existe
            if (!data.password || data.password === '') {
                data.password = '12345678';
            }
            if (data.password_confirmation === undefined) {
                data.password_confirmation = data.password;
            }

            try {
                await fetchAPI('/coordenadores', {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                closeModal();
                carregarCoordenadores();
                carregarDashboard();
                alert('✅ Coordenador criado com sucesso!');
            } catch (e) {
                alert('❌ Erro ao criar coordenador: ' + e.message);
            }
        }

        // Funções auxiliares para carregar selects
        async function carregarOpcoesCursoParaSelect(selector, selectedId = null) {
            try {
                const data = await fetchAPI('/cursos');
                const select = document.querySelector(selector);
                if (select && data.data) {
                    select.innerHTML = '<option value="">Selecione um curso...</option>' +
                        data.data.map(c =>
                            `<option value="${c.id}" ${selectedId == c.id ? 'selected' : ''}>${c.nome}</option>`).join(
                            '');
                }
            } catch (e) {
                console.error(e);
            }
        }

        async function carregarOpcoesInstituicaoParaSelect(selector, selectedId = null) {
            try {
                const data = await fetchAPI('/instituicoes');
                const select = document.querySelector(selector);
                if (select && data.data) {
                    select.innerHTML = '<option value="">Selecione uma instituição...</option>' +
                        data.data.map(i =>
                            `<option value="${i.id}" ${selectedId == i.id ? 'selected' : ''}>${i.nome_instituicao}</option>`
                        ).join('');
                }
            } catch (e) {
                console.error(e);
            }
        }

        function showContent(section) {
            document.querySelectorAll('.content-area').forEach(el => el.classList.remove('active'));
            const contentEl = document.getElementById(`content-${section}`);
            if (contentEl) contentEl.classList.add('active');

            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            const activeLink = document.querySelector(`.nav-link[data-section="${section}"]`);
            if (activeLink) activeLink.classList.add('active');

            if (section === 'instituicoes') carregarInstituicoes();
            if (section === 'cursos') carregarCursos();
            if (section === 'coordenadores') carregarCoordenadores();
            if (section === 'dashboard') carregarDashboard();
        }

        function openModal(type) {
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modal-title');
            const modalBody = document.getElementById('modal-body');

            if (type === 'instituicao') {
                modalTitle.innerText = '➕ Nova Instituição';
                modalBody.innerHTML = `
                    <form id="dynamicForm" onsubmit="submitInstituicao(event)">
                        <div class="form-group">
                            <label>Nome <span class="required">*</span></label>
                            <input name="nome_instituicao" required>
                        </div>
                        <div class="form-group">
                            <label>Sigla <span class="required">*</span></label>
                            <input name="sigla" required>
                        </div>
                        <div class="form-group">
                            <label>CNPJ <span class="required">*</span></label>
                            <input name="cnpj" id="cnpjInput" placeholder="00.000.000/0000-00" required>
                            <small class="help-text">Digite apenas números ou use o formato 00.000.000/0000-00</small>
                        </div>
                        <div class="form-group">
                            <label>Endereço <span class="required">*</span></label>
                            <input name="endereco" required>
                        </div>
                        <div class="form-group">
                            <label>Cidade <span class="required">*</span></label>
                            <input name="cidade" required>
                        </div>
                        <div class="form-group">
                            <label>Estado (UF) <span class="required">*</span></label>
                            <input name="estado" maxlength="2" placeholder="SP" required>
                        </div>
                        <div class="form-group">
                            <label>Telefone</label>
                            <input name="telefone" placeholder="(00) 00000-0000">
                        </div>
                        <div class="form-group">
                            <label>E-mail de Contato</label>
                            <input name="email_contato" type="email">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                `;
                const cnpjInput = document.getElementById('cnpjInput');
                if (cnpjInput) {
                    cnpjInput.addEventListener('input', function() {
                        mascaraCNPJ(this);
                    });
                }
            } else if (type === 'curso') {
                modalTitle.innerText = '📖 Novo Curso';
                modalBody.innerHTML = `
                    <form id="dynamicForm" onsubmit="submitCurso(event)">
                        <div class="form-group"><label>Nome <span class="required">*</span></label><input name="nome" required></div>
                        <div class="form-group"><label>Código <span class="required">*</span></label><input name="codigo" required></div>
                        <div class="form-group"><label>Carga Horária Estágio <span class="required">*</span></label><input name="carga_horaria_estagio" type="number" required></div>
                        <div class="form-group">
                            <label>Modalidade <span class="required">*</span></label>
                            <select name="modalidade" required>
                                <option value="Presencial">Presencial</option>
                                <option value="EAD">EAD</option>
                                <option value="Hibrido">Híbrido</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Criar Curso</button>
                    </form>
                `;
            } else {
                modalTitle.innerText = '👨‍🏫 Novo Coordenador';
                modalBody.innerHTML = `
                    <form id="dynamicForm" onsubmit="submitCoordenador(event)">
                        <div class="form-group"><label>Nome Completo <span class="required">*</span></label><input name="name" required></div>
                        <div class="form-group"><label>E-mail <span class="required">*</span></label><input name="email" type="email" required></div>
                        <div class="form-group"><label>Senha <span class="required">*</span></label><input name="password" type="password" required></div>
                        <div class="form-group"><label>Matrícula Institucional <span class="required">*</span></label><input name="matricula_institucional" required></div>
                        <div class="form-group"><label>Data Início Função <span class="required">*</span></label><input name="data_inicio_funcao" type="date" required></div>
                        <div class="form-group"><label>Curso <span class="required">*</span></label><select name="curso_id" required><option value="">Selecione...</option></select></div>
                        <div class="form-group"><label>Instituição <span class="required">*</span></label><select name="instituicao_id" required><option value="">Selecione...</option></select></div>
                        <button type="submit" class="btn btn-primary">Registrar Coordenador</button>
                    </form>
                `;
                carregarOpcoesCurso();
                carregarOpcoesInstituicao();
            }
            modal.style.display = 'flex';
        }

        async function submitInstituicao(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            let data = Object.fromEntries(formData.entries());

            if (data.cnpj) {
                data.cnpj = data.cnpj.replace(/[^\d]/g, '');
                if (data.cnpj.length !== 14) {
                    alert('CNPJ deve conter 14 dígitos. Você informou ' + data.cnpj.length + ' dígitos.');
                    return;
                }
            }

            try {
                await fetchAPI('/instituicoes', {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                closeModal();
                carregarInstituicoes();
                carregarDashboard();
                alert('✅ Instituição criada com sucesso!');
            } catch (e) {
                alert('❌ Erro ao criar instituição: ' + e.message);
            }
        }

        async function submitCurso(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            try {
                await fetchAPI('/cursos', {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                closeModal();
                carregarCursos();
                carregarDashboard();
                alert('✅ Curso criado com sucesso!');
            } catch (e) {
                alert('❌ Erro ao criar curso: ' + e.message);
            }
        }

        async function submitCoordenador(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            data.password = data.password || '12345678';
            try {
                await fetchAPI('/coordenadores', {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                closeModal();
                carregarCoordenadores();
                carregarDashboard();
                alert('✅ Coordenador criado com sucesso!');
            } catch (e) {
                alert('❌ Erro ao criar coordenador: ' + e.message);
            }
        }

        async function carregarOpcoesCurso() {
            try {
                const data = await fetchAPI('/cursos');
                const select = document.querySelector('select[name="curso_id"]');
                if (select && data.data) {
                    select.innerHTML = '<option value="">Selecione um curso...</option>' +
                        data.data.map(c => `<option value="${c.id}">${c.nome}</option>`).join('');
                }
            } catch (e) {
                console.error(e);
            }
        }

        async function carregarOpcoesInstituicao() {
            try {
                const data = await fetchAPI('/instituicoes');
                const select = document.querySelector('select[name="instituicao_id"]');
                if (select && data.data) {
                    select.innerHTML = '<option value="">Selecione uma instituição...</option>' +
                        data.data.map(i => `<option value="${i.id}">${i.nome_instituicao}</option>`).join('');
                }
            } catch (e) {
                console.error(e);
            }
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function gerarRelatorio(tipo) {
            alert(`📄 Gerando relatório de ${tipo}...`);
        }

        function exportarCSV() {
            alert('📎 Exportação CSV será implementada via API.');
        }
        window.onclick = function(e) {
            if (e.target === document.getElementById('modal')) closeModal();
        };

        // Inicialização
        carregarDashboard();
    </script>
</body>

</html>
