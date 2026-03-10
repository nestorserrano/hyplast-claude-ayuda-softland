# Hyplast Claude Ayuda Softland - Asistente IA con Claude AI

## Descripción
Sistema de ayuda inteligente integrado con Claude AI de Anthropic para consultar documentación de Softland, buscar manuales y obtener respuestas contextuales.

## Características Principales
- 🤖 Integración con Claude AI (Anthropic)
- 📚 Búsqueda en manuales de Softland
- 💡 Respuestas contextuales
- 📝 Indexación de documentos PDF
- 🔍 Búsqueda semántica
- 💬 Chat interactivo
- 📊 Historial de consultas

## Modelos Principales
- **SoftlandKnowledgeBase**: Base de conocimientos
- **Category**: Categorías de ayuda

## Funcionalidades
- Búsqueda en documentación Softland
- Consultas sobre módulos (Contabilidad, Inventario, etc.)
- Respuestas basadas en IA con contexto
- Indexación automática de PDFs
- Sistema de categorías

## API Endpoints
```
POST   /api/ayuda/search           # Buscar en documentación
POST   /api/ayuda/ask              # Preguntar a Claude
GET    /api/ayuda/categories       # Listar categorías
POST   /api/ayuda/index            # Indexar documento
```

## Configuración
```env
ANTHROPIC_API_KEY=tu_api_key_claude
CLAUDE_MODEL=claude-3-sonnet-20240229
```

## Categorías Soportadas
- Contabilidad
- Inventario
- Bancos
- Cuentas por Cobrar
- Cuentas por Pagar
- RRHH

## Requisitos
- PHP >= 8.1
- Laravel >= 10.x
- API Key de Anthropic (Claude)

## Instalación
```bash
composer install
php artisan migrate
php artisan ayuda:index-manuales
```

## Licencia
Propietario - Hyplast © 2026
