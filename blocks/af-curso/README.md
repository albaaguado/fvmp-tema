# Bloque FC-Curso (doo/fc-curso)

Bloque dinámico para páginas de detalle de cursos/acciones formativas. **Se inserta automáticamente en una Página de WordPress** cuando se crea una nueva "Acción Formativa".

## Flujo de Trabajo Completo

### 1. Admin crea "Acción Formativa"

1. **WordPress Admin → Acciones Formativas → Añadir nueva**
2. **Rellenar meta fields**:
   - Código (ej: AE01.1)
   - Duración (ej: 5 horas)
   - Plazas (ej: 170 plazas)
   - Fecha inicio (dd/mm/aaaa)
   - Fecha fin (dd/mm/aaaa)
3. **Seleccionar taxonomías**:
   - Área Temática (sidebar derecha)
   - Modalidad (sidebar derecha): Online, Presencial, Streaming, Semipresencial, Mix Online - Presencial, Mix Online - Streaming
4. **Publicar**

### 2. Sistema crea automáticamente una Página

Cuando se publica la Acción Formativa, **automáticamente**:

1. Se crea una Página de WordPress con:
   - Mismo título que el CPT
   - Mismo slug que el CPT
   - Bloque `doo/fc-curso` pre-insertado en el contenido
2. Se vincula la Página al CPT mediante post meta:
   - CPT → `doo_linked_page_id` (ID de la página)
   - Página → `doo_linked_cpt_id` (ID del CPT)

### 3. El bloque lee datos automáticamente

El bloque `doo/fc-curso` en la Página vinculada **lee automáticamente**:

- ✅ Título del curso (desde `post_title` del CPT)
- ✅ Código (desde `doo_fc_code`)
- ✅ Duración (desde `doo_fc_hours`)
- ✅ Plazas (desde `doo_fc_seats`)
- ✅ Modalidad (desde taxonomy `doo_modalidad`)
- ✅ Fechas formateadas (desde `doo_fc_date_from` y `doo_fc_date_to`)
  - Entrada: `24/05/2025` y `24/06/2025`
  - Salida: `24 may - 24 jun`

### 4. Admin completa detalles del curso en la Página

1. Editar la Página auto-creada
2. Desde el panel lateral (InspectorControls), añadir:
   - **Objetivos** (Panel "Objetivos")
   - **Temas/Contenido** (Panel "Temas del contenido")
   - **Destinatarios** (Panel "Panel lateral")
   - **Horario** (Panel "Panel lateral")
   - **URL de inscripción** (Panel "Panel lateral")

## Problema con defaults extensos (RESUELTO)

**IMPORTANTE**: Los valores por defecto de `objectives` y `topics` están vacíos (`[]`) en `block.json` para evitar error **414 (Request-URI Too Large)**.

### ¿Por qué?

`ServerSideRender` envía TODOS los atributos del bloque en la URL (query string GET). Con defaults extensos (7 topics con múltiples items cada uno), la URL supera el límite del servidor (~8KB) causando error 414.

### Solución implementada

- Defaults vacíos en `block.json`
- El usuario añade contenido desde InspectorControls (panel lateral del editor)
- La URL se mantiene corta en la carga inicial

## Cards en la Página FC

En la página de Formación Continua (FC), las cards muestran:

- Área Temática (badge color teal)
- Código del curso
- Título del curso
- Duración + Plazas + Modalidad + Fechas (con iconos)
- Botón "Ver más información" → Enlace a la Página de detalle

Los datos se extraen **directamente del CPT** mediante `WP_Query`, NO del bloque.

## Archivos Relacionados

- `inc/fc-post-type.php` - Registro del CPT, taxonomías, meta fields, auto-creación de páginas
- `blocks/fc-curso/block.json` - Definición del bloque (defaults vacíos)
- `blocks/fc-curso/render.php` - Renderizado PHP server-side (lee del CPT vinculado)
- `src/blocks/fc/curso/edit.jsx` - Componente editor (InspectorControls)
- `src/scss/layout/fc/_fc-curso.scss` - Estilos del bloque

## Notas Técnicas

- **PHP 7.4 compatibility**: Usa `strpos()` en lugar de `str_starts_with()`
- **Color theming**: Variables CSS inline desde atributos del bloque
- **Responsive**: Grid de 2 columnas → 1 columna en móvil
- **Taxonomías**: 6 modalidades predefinidas (seed automático)
- **Auto-vinculación**: Hook `wp_after_insert_post` crea la página automáticamente
