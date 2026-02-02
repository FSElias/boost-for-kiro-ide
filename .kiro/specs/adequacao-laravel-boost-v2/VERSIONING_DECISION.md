# Decisão de Versionamento: Laravel Boost v2.0 Support

## Data da Análise
2024-01-15

## Contexto

O pacote `boost-for-kiro-ide` está sendo atualizado para suportar Laravel Boost v2.0, mudando a dependência de `^1.0` para `^2.0`. Esta decisão de versionamento determina qual será o próximo número de versão do pacote.

## Versão Atual

**Última versão publicada**: `1.0.4` (2025-11-17)

## Análise de Impacto

### Mudanças Técnicas

1. **Dependência do Laravel Boost**:
   - Antes: `"laravel/boost": "^1.0"`
   - Depois: `"laravel/boost": "^2.0"`

2. **Código do Pacote**:
   - ✅ Nenhuma mudança nas classes principais (Kiro, ServiceProvider)
   - ✅ Interfaces permanecem compatíveis (Agent, McpClient, CodeEnvironment)
   - ✅ Todos os métodos mantêm as mesmas assinaturas
   - ✅ Paths de configuração permanecem inalterados

3. **Funcionalidades Novas**:
   - ✅ Sistema de Skills funciona automaticamente (zero configuration)
   - ✅ Comando `boost:add-skill` disponível
   - ✅ Melhorias na experiência de instalação

### Impacto nos Usuários

#### Cenário 1: Usuário com Laravel Boost v1.x

**Situação Atual**:
```json
{
  "require-dev": {
    "laravel/boost": "^1.0",
    "jcf/boost-for-kiro-ide": "^1.0"
  }
}
```

**Após Atualização do boost-for-kiro-ide**:

Se o usuário executar `composer update jcf/boost-for-kiro-ide`:

- ❌ **Composer NÃO permitirá a atualização** devido ao conflito de dependências
- O Composer reportará: "Your requirements could not be resolved to an installable set of packages"
- Mensagem indicará conflito entre `laravel/boost ^1.0` (instalado) e `laravel/boost ^2.0` (requerido)

**Conclusão**: Usuários em Laravel Boost v1.x **não conseguirão** instalar a nova versão do pacote sem atualizar o Laravel Boost.

#### Cenário 2: Usuário que Atualiza Ambos

**Comando**:
```bash
composer update jcf/boost-for-kiro-ide laravel/boost
```

**Resultado**:
- ✅ Ambos os pacotes são atualizados
- ✅ Código do usuário continua funcionando (nenhuma mudança de API)
- ✅ Novas funcionalidades (Skills) disponíveis automaticamente
- ⚠️ Usuário precisa re-executar `php artisan boost:install` (recomendado)

**Conclusão**: Atualização é suave, mas requer ação consciente do usuário.

## Princípios de Versionamento Semântico

Segundo [Semantic Versioning 2.0.0](https://semver.org/):

> Given a version number MAJOR.MINOR.PATCH, increment the:
> 
> 1. **MAJOR** version when you make incompatible API changes
> 2. **MINOR** version when you add functionality in a backward compatible manner
> 3. **PATCH** version when you make backward compatible bug fixes

### Análise Aplicada ao Nosso Caso

#### É uma Mudança de API Incompatível (MAJOR)?

**Argumentos a Favor (MAJOR bump)**:
1. ❌ Usuários em Laravel Boost v1.x não podem usar esta versão
2. ❌ Requer atualização de dependência externa (Laravel Boost)
3. ❌ Composer bloqueará a instalação sem atualizar Laravel Boost
4. ❌ Pode quebrar builds de CI/CD que não especificam versões exatas

**Argumentos Contra (NÃO é MAJOR)**:
1. ✅ A API do pacote não mudou (mesmas classes, métodos, assinaturas)
2. ✅ Código do usuário não precisa ser modificado
3. ✅ Nenhuma interface foi alterada ou removida
4. ✅ Comportamento existente permanece idêntico

#### É Adição de Funcionalidade Compatível (MINOR)?

**Argumentos a Favor (MINOR bump)**:
1. ✅ Adiciona suporte ao sistema de Skills (nova funcionalidade)
2. ✅ Código existente continua funcionando sem modificações
3. ✅ API do pacote permanece compatível
4. ✅ Usuários podem optar por não usar Skills

**Argumentos Contra (NÃO é MINOR)**:
1. ❌ Requer mudança de dependência externa
2. ❌ Não é uma atualização "drop-in" para todos os usuários

## Precedentes na Comunidade

### Exemplo 1: Laravel Framework

Laravel frequentemente faz major bumps quando muda requisitos de dependências:
- Laravel 9 → 10: Mudou requisito de PHP 8.0 → 8.1 (MAJOR bump)
- Laravel 10 → 11: Mudou requisito de PHP 8.1 → 8.2 (MAJOR bump)

**Justificativa**: Mesmo sem mudanças de API, mudanças em requisitos de sistema são consideradas breaking changes.

### Exemplo 2: Pacotes do Ecossistema Laravel

Muitos pacotes seguem a versão do Laravel:
- `laravel/sanctum`: v2.x para Laravel 8, v3.x para Laravel 9+
- `spatie/laravel-permission`: v5.x para Laravel 9, v6.x para Laravel 10+

**Justificativa**: Major bump facilita comunicação clara sobre compatibilidade.

### Exemplo 3: Pacotes de Integração

Pacotes que integram com serviços externos frequentemente fazem major bump quando mudam versões de dependências:
- `guzzlehttp/guzzle`: v6 → v7 quando mudou requisitos de PHP
- `symfony/http-foundation`: Major bump a cada mudança de requisitos

## Opções de Versionamento

### Opção A: Versão 2.0.0 (MAJOR bump) ⭐ RECOMENDADO

**Número de Versão**: `2.0.0`

**Justificativa**:
1. **Comunicação Clara**: Sinaliza claramente que há mudança de requisitos
2. **Alinhamento com Laravel Boost**: Versão 2.x do pacote para Laravel Boost 2.x
3. **Prevenção de Surpresas**: Usuários sabem que precisam verificar requisitos
4. **Precedente da Comunidade**: Segue padrão do Laravel e ecossistema
5. **Composer Constraints**: Usuários podem especificar `^1.0` para Laravel Boost v1.x e `^2.0` para v2.x

**Vantagens**:
- ✅ Comunicação explícita de mudança de requisitos
- ✅ Facilita manutenção de múltiplas versões (1.x para Boost v1, 2.x para Boost v2)
- ✅ Usuários entendem imediatamente que há mudança significativa
- ✅ Alinhamento semântico com a versão do Laravel Boost
- ✅ Evita confusão sobre compatibilidade

**Desvantagens**:
- ⚠️ Pode parecer "grande demais" para quem vê apenas mudança de dependência
- ⚠️ Requer atualização consciente de constraints no composer.json dos usuários

**Exemplo de Uso**:
```json
{
  "require-dev": {
    "laravel/boost": "^2.0",
    "jcf/boost-for-kiro-ide": "^2.0"
  }
}
```

### Opção B: Versão 1.1.0 (MINOR bump)

**Número de Versão**: `1.1.0`

**Justificativa**:
1. **API Compatível**: Nenhuma mudança na API do pacote
2. **Adição de Funcionalidade**: Suporte ao sistema de Skills
3. **Código Não Muda**: Usuários não precisam modificar código

**Vantagens**:
- ✅ Tecnicamente correto do ponto de vista de API
- ✅ Menor "impacto psicológico" para usuários

**Desvantagens**:
- ❌ Não comunica claramente a mudança de requisitos
- ❌ Usuários podem tentar atualizar e encontrar erro de dependência
- ❌ Dificulta manutenção de versões para Laravel Boost v1.x vs v2.x
- ❌ Não segue precedente da comunidade Laravel
- ❌ Pode causar confusão: "Por que não consigo instalar v1.1.0?"

**Exemplo de Uso**:
```json
{
  "require-dev": {
    "laravel/boost": "^1.0",
    "jcf/boost-for-kiro-ide": "^1.1"  // ❌ Composer bloqueará!
  }
}
```

### Opção C: Versão 1.0.5 (PATCH bump)

**Número de Versão**: `1.0.5`

**Justificativa**: Nenhuma - esta opção não é apropriada.

**Por que NÃO usar**:
- ❌ PATCH é apenas para bug fixes
- ❌ Mudança de dependência não é bug fix
- ❌ Adiciona funcionalidade (Skills)
- ❌ Viola completamente SemVer

## Recomendação Final

### ⭐ Versão Recomendada: **2.0.0** (MAJOR bump)

**Razões Principais**:

1. **Alinhamento com Laravel Boost**: Versão 2.x do pacote para Laravel Boost 2.x cria correspondência clara e intuitiva.

2. **Comunicação Clara**: Major bump sinaliza explicitamente que há mudança de requisitos, evitando surpresas para usuários.

3. **Precedente da Comunidade**: Segue o padrão estabelecido pelo Laravel e ecossistema de fazer major bump quando requisitos de sistema mudam.

4. **Manutenção Simplificada**: Facilita manter branch 1.x para Laravel Boost v1.x e branch 2.x para Laravel Boost v2.x, se necessário.

5. **Composer Constraints**: Permite que usuários especifiquem claramente qual versão querem:
   - `"jcf/boost-for-kiro-ide": "^1.0"` → Laravel Boost v1.x
   - `"jcf/boost-for-kiro-ide": "^2.0"` → Laravel Boost v2.x

6. **Experiência do Usuário**: Usuários que veem "2.0.0" sabem imediatamente que devem verificar requisitos e changelog antes de atualizar.

### Justificativa Técnica

Embora a API do pacote não tenha mudado, a **mudança de requisitos de dependência é uma breaking change do ponto de vista do usuário**:

- Usuários em Laravel Boost v1.x **não podem** usar esta versão
- Requer ação consciente para atualizar (não é drop-in replacement)
- Composer bloqueará a instalação sem atualizar Laravel Boost

Segundo SemVer, uma breaking change é qualquer mudança que requer ação do usuário para adotar. Neste caso, usuários precisam atualizar Laravel Boost, o que constitui uma breaking change.

### Comparação com Alternativas

| Critério | v2.0.0 (MAJOR) | v1.1.0 (MINOR) |
|----------|----------------|----------------|
| Comunicação Clara | ✅ Excelente | ❌ Confusa |
| Alinhamento com Boost | ✅ Sim (2.x → 2.x) | ❌ Não (1.x → 2.x) |
| Precedente Comunidade | ✅ Segue Laravel | ❌ Não segue |
| Manutenção Múltiplas Versões | ✅ Fácil | ⚠️ Difícil |
| Composer Constraints | ✅ Intuitivo | ❌ Confuso |
| Experiência do Usuário | ✅ Clara | ❌ Pode causar erros |

## Plano de Implementação

### 1. Atualizar CHANGELOG.md

Mudar de:
```markdown
## [Unreleased]
```

Para:
```markdown
## [2.0.0] - 2024-01-15
```

### 2. Criar Tag Git

```bash
git commit -am "Release v2.0.0 - Laravel Boost v2.0 support"
git tag -a v2.0.0 -m "Release v2.0.0 - Laravel Boost v2.0 support"
git push origin main
git push origin v2.0.0
```

### 3. Criar GitHub Release

**Título**: `v2.0.0 - Laravel Boost v2.0 Support`

**Descrição**:
```markdown
## 🚀 Laravel Boost v2.0 Support

This major release updates the package to support Laravel Boost v2.0, bringing compatibility with the new Skills system and improved installation experience.

### ⚠️ Breaking Changes

- **Requires Laravel Boost ^2.0**: This version is not compatible with Laravel Boost v1.x
- Users must update Laravel Boost to v2.0 to use this version

### ✨ What's New

- **Skills System Support**: Zero-configuration support for Laravel Boost v2.0 Skills
- **Enhanced Installation**: Improved code environment detection and setup
- **New Command**: Access to `boost:add-skill` for installing specialized AI instructions

### 📦 Migration Guide

To upgrade from v1.x:

1. Update dependencies:
   ```bash
   composer update jcf/boost-for-kiro-ide laravel/boost
   ```

2. Re-run Boost installation:
   ```bash
   php artisan boost:install
   ```

3. (Optional) Install Skills:
   ```bash
   php artisan boost:add-skill owner/repo
   ```

### 🔧 Technical Details

- No code changes required in your application
- All existing functionality remains compatible
- Skills work automatically with Kiro IDE (zero configuration)

For detailed information, see [CHANGELOG.md](CHANGELOG.md) and [docs/SKILLS_SYSTEM.md](docs/SKILLS_SYSTEM.md).

### 📚 Documentation

- [Skills System Documentation](docs/SKILLS_SYSTEM.md)
- [Manual Testing Guide](tests/Integration/MANUAL_TESTING_GUIDE.md)
- [README](README.md)
```

### 4. Verificar Packagist

Após push da tag, verificar que Packagist detectou a nova versão:

```bash
# Aguardar alguns minutos para webhook processar
composer show jcf/boost-for-kiro-ide --all
```

Deve mostrar `2.0.0` como versão disponível.

### 5. Comunicação

**Anunciar em**:
- GitHub Release (feito no passo 3)
- README badges (atualização automática)
- Packagist (atualização automática via webhook)

**Mensagem Chave**: "Major version bump to align with Laravel Boost v2.0 - requires Laravel Boost ^2.0"

## Manutenção de Versões Antigas

### Branch 1.x

Se necessário manter suporte para Laravel Boost v1.x:

1. Criar branch `1.x` a partir da última versão 1.0.4:
   ```bash
   git checkout v1.0.4
   git checkout -b 1.x
   git push origin 1.x
   ```

2. Bug fixes para Laravel Boost v1.x podem ser aplicados em `1.x` e lançados como `1.0.5`, `1.0.6`, etc.

3. Novas funcionalidades vão para `main` (versão 2.x)

### Política de Suporte

**Recomendação**:
- **v2.x (main)**: Suporte ativo, novas funcionalidades
- **v1.x**: Apenas bug fixes críticos por 6 meses, depois descontinuado

Adicionar ao README:
```markdown
## Version Support

| Version | Laravel Boost | Support Status |
|---------|---------------|----------------|
| 2.x     | ^2.0          | ✅ Active      |
| 1.x     | ^1.0          | ⚠️ Bug fixes only (until July 2024) |
```

## Conclusão

**Decisão Final**: Usar versão **2.0.0** (MAJOR bump)

**Justificativa Resumida**: 
- Alinhamento claro com Laravel Boost v2.0
- Comunicação explícita de mudança de requisitos
- Segue precedente da comunidade Laravel
- Facilita manutenção e compreensão de compatibilidade
- Melhor experiência do usuário a longo prazo

**Próximos Passos**:
1. ✅ Documentar decisão (este arquivo)
2. ⏳ Atualizar CHANGELOG.md com versão 2.0.0 e data
3. ⏳ Criar tag Git v2.0.0
4. ⏳ Criar GitHub Release
5. ⏳ Verificar publicação no Packagist
6. ⏳ Marcar task 10.1 como concluída

---

**Documento criado por**: Kiro AI Agent  
**Data**: 2024-01-15  
**Baseado em**: Semantic Versioning 2.0.0, precedentes da comunidade Laravel, análise de impacto técnico
