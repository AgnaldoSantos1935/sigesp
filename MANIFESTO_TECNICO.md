<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

1️⃣ IDENTIDADE DO SISTEMA

Nome: SIGESP — Sistema de Gestão Pública
Natureza: Plataforma SaaS (Software as a Service)
Finalidade: Gestão administrativa, contratual, financeira, documental e de fiscalização para órgãos públicos, com foco em governança, rastreabilidade e conformidade legal.

O SIGESP não é um sistema departamental.
É uma plataforma institucional, preparada para múltiplos órgãos, contratos complexos e auditoria externa.

2️⃣ PRINCÍPIO FUNDAMENTAL

O SIGESP não modela telas.
Ele modela fatos administrativos.

Tudo no sistema deve responder à pergunta:

“Por que isso existe, quem decidiu, quando, com base em qual documento?”

Se o sistema não responde isso sozinho, a modelagem está errada.

3️⃣ ARQUITETURA GERAL
🔧 Tecnologias base

Backend: Laravel 12

Banco de dados: MySQL 8+

Engine: InnoDB

Charset: utf8mb4

Frontend: Blade (base), componentes avançados opcionais

Armazenamento: Laravel Storage (local, preparado para S3/MinIO)

4️⃣ ARQUITETURA SaaS (MULTI-TENANT)
🧱 Modelo adotado

Single Database

Isolamento lógico por tenant_id

📌 Regras obrigatórias

Todo dado de negócio pertence a um tenant

Nenhuma entidade crítica existe sem tenant_id

O tenant é resolvido automaticamente pelo sistema

O usuário não escolhe o cliente no login

O frontend nunca informa tenant_id

🔐 Segurança

Isolamento garantido por:

TenantResolver

Middleware global

Policies

Global Scopes

SuperAdmin SaaS é exceção controlada

5️⃣ PRINCÍPIOS DE MODELAGEM (ANTI-CRUD)

São obrigatórios, não sugestões:

❌ Não sobrescrever fatos administrativos

✅ Toda mudança relevante gera histórico

❌ Não misturar instrumento, execução e pagamento

✅ Separar:

Instrumento jurídico

Contrato

Item contratual

Execução

Medição

Pagamento

❌ Não colocar regra de negócio em Controllers

✅ Usar Services, Policies e Domain Logic

❌ Exclusão física de dados críticos

✅ Status e ciclo de vida explícitos

CRUD é apenas a camada de entrada, nunca o sistema.

6️⃣ DOCUMENTO ≠ ARQUIVO
📄 Documento administrativo

É um ato formal, com:

tipologia

contexto

validade

vínculo

responsável

status

📎 Arquivo

É apenas a representação digital do documento.

📌 O SIGESP gerencia documentos, não “uploads”.

Todo documento:

pode existir sem arquivo

pode ter versões

pode ser vinculado a qualquer módulo

é transversal e auditável

7️⃣ FLUXO ADMINISTRATIVO FUNDAMENTAL

Execução → Medição → Ateste → Pagamento

Nunca:

pagamento antes da execução

execução sem autorização

medição sem OS

ateste sem documento

Esse fluxo é inalterável, independentemente do tipo de contrato.

8️⃣ CONTRATOS E INSTRUMENTOS JURÍDICOS
📜 Instrumento Jurídico

Base legal e estrutural (Model: `InstrumentoJuridico`)
Polimorfismo Lógico:
- Contratos (Prestação de Serviços ou Aquisição de Bens)
- Convênios (Cooperação)

Legitima o acordo.
Não executa nem paga.

📑 Contrato / Convênio

Define objeto, regras, vigência e categoria.
Categorias Obrigatórias:
- Prestação de Serviços
- Aquisição de Bens

Não executa nem paga sozinho.

📦 Item Contratual

Unidade real de controle (Model: `InstrumentoJuridicoItem`)
Onde mora:
empenho
execução
medição
pagamento

📍 Distribuição de Itens

O item DEVE ser distribuído para unidades consumidoras.
Suporte Polimórfico (Model: `InstrumentoJuridicoItem` -> `unidades`):
- Unidades Escolares
- Unidades Administrativas

Controle de saldo e quantidade individualizado por unidade.
A soma das distribuições valida o total do item.

9️⃣ FISCALIZAÇÃO E RESPONSABILIDADE

Designações (fiscal, gestor) nunca são sobrescritas

Toda designação:

tem período

tem portaria

tem histórico

Fiscal valida, não executa

Fornecedor executa, não valida

🔟 FÁBRICA DE SOFTWARE

Contratos de fábrica de software possuem lógica própria:

O objeto é capacidade produtiva

Medição por:

UST

Pontos de Função

Horas (restrito)

Complexidade é regra, não texto

Demanda ≠ Ordem de Serviço

Pagamento só ocorre após:

medição validada

ateste técnico documentado

1️⃣1️⃣ MÓDULOS TRANSVERSAIS

São considerados infraestrutura do sistema:

Gestão Documental

RBAC / Permissões

Auditoria

Multi-tenant

Notificações

Histórico administrativo

Nenhum módulo pode “reinventar” essas funções.

1️⃣2️⃣ SEEDERS E DADOS DE TESTE

Seeders são obrigatórios

Dados de teste devem:

representar cenários reais

respeitar tenant

permitir demonstração comercial

Banco vazio não é ambiente válido

1️⃣3️⃣ VISÃO DE PRODUTO (SaaS)

O SIGESP é:

modular

parametrizável

comercializável

escalável

auditável

Clientes diferentes usam:

o mesmo core

com módulos e limites distintos

1️⃣4️⃣ REGRA FINAL (A MAIS IMPORTANTE)

Se daqui a 3 ou 5 anos alguém perguntar
“por que isso está assim?”,
o SIGESP deve responder sozinho.

Se não responder, a modelagem falhou.

