# 🥭 Guía de Diseño de Formularios - Empaque Mango

## Descripción General
Este diseño ofrece un sistema de estilos consistente y reutilizable para todos los formularios del sistema, usando una paleta de colores en **amarillos**, **verdes** y **naranjas** que representa la temática del mango.

## 🎨 Paleta de Colores

### Colores Principales
- **Amarillo Primario**: `#FFC107` - Para botones principales y elementos destacados
- **Amarillo Claro**: `#FFD54F` - Para fondos y efectos hover
- **Amarillo Oscuro**: `#FF8F00` - Para estados activos

- **Verde Primario**: `#4CAF50` - Para botones de acción positiva
- **Verde Claro**: `#81C784` - Para efectos y gradientes
- **Verde Oscuro**: `#388E3C` - Para texto en estados activos

- **Naranja Acento**: `#FF8A65` - Para botones de cancelar/salir (uso moderado)
- **Naranja Hover**: `#FF7043` - Para efectos hover en naranjas

## 🏗️ Estructura de Formularios

### 1. Contenedor Principal
```html
<div class="mango-container"> <!-- o container-mostrar -->
    <!-- Tu contenido aquí -->
</div>
```

### 2. Título/Encabezado
```html
<div class="alert alert-primary mt-5" role="alert">
    <h1 id="titulo">Título de tu Formulario</h1>
</div>
```

### 3. Separador Visual
```html
<div class="separator-mango"></div>
```

### 4. Formulario
```html
<div id="formulario-alta" style="display: none;">
    <form id="form-alta" method="POST" onsubmit="enviarFormulario(event)">
        <div class="row">
            <!-- Campos del formulario aquí -->
        </div>
        <!-- Botones aquí -->
    </form>
</div>
```

## 📝 Campos de Formulario

### Campo de Texto
```html
<div class="form-group mb-3">
    <label class="form-label">
        <i class="fas fa-user"></i>Nombre:
    </label>
    <input type="text" name="nombre" class="form-control" 
           placeholder="Ingresa el nombre" required />
</div>
```

### Campo de Email
```html
<div class="form-group mb-3">
    <label class="form-label">
        <i class="fas fa-envelope"></i>Email:
    </label>
    <input type="email" name="email" class="form-control" 
           placeholder="ejemplo@correo.com" required />
</div>
```

### Campo de Teléfono
```html
<div class="form-group mb-3">
    <label class="form-label">
        <i class="fas fa-phone"></i>Teléfono:
    </label>
    <input type="tel" name="telefono" class="form-control" 
           placeholder="(555) 123-4567" required />
</div>
```

### Campo de Número
```html
<div class="form-group mb-3">
    <label class="form-label">
        <i class="fas fa-hashtag"></i>Cantidad:
    </label>
    <input type="number" name="cantidad" class="form-control" 
           placeholder="0" min="0" required />
</div>
```

### Campo de Fecha
```html
<div class="form-group mb-3">
    <label class="form-label">
        <i class="fas fa-calendar"></i>Fecha:
    </label>
    <input type="date" name="fecha" class="form-control" required />
</div>
```

### Campo de Selección (Select)
```html
<div class="form-group mb-3">
    <label class="form-label">
        <i class="fas fa-list"></i>Categoría:
    </label>
    <select name="categoria" class="form-control" required>
        <option value="">Selecciona una categoría</option>
        <option value="1">Categoría 1</option>
        <option value="2">Categoría 2</option>
    </select>
</div>
```

### Campo de Área de Texto
```html
<div class="form-group mb-3">
    <label class="form-label">
        <i class="fas fa-comment"></i>Descripción:
    </label>
    <textarea name="descripcion" class="form-control" rows="4" 
              placeholder="Escribe una descripción..." required></textarea>
</div>
```

### Radio Buttons
```html
<div class="form-group mb-3">
    <label class="form-label">
        <i class="fas fa-check-circle"></i>Tipo:
    </label>
    <div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipo" 
                   id="tipo_a" value="A" required>
            <label class="form-check-label" for="tipo_a">Tipo A</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipo" 
                   id="tipo_b" value="B" required>
            <label class="form-check-label" for="tipo_b">Tipo B</label>
        </div>
    </div>
</div>
```

### Checkbox
```html
<div class="form-group mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="activo" 
               id="activo" value="1">
        <label class="form-check-label" for="activo">
            <i class="fas fa-toggle-on"></i>Activo
        </label>
    </div>
</div>
```

## 🔘 Botones

### Grupo de Botones Principal
```html
<div class="d-grid gap-2 col-6 mx-auto">
    <button class="btn btn-primary" type="submit">
        <i class="fas fa-save"></i>Guardar
    </button>
    <button class="btn btn-danger" type="button" onclick="ocultarFormulario()">
        <i class="fas fa-times"></i>Salir
    </button>
</div>
```

### Botón de Crear (para tablas)
```html
<button class="btn btn-success btn-sm" onclick="mostrarFormulario(this)" 
        data-title="Crear Nuevo Elemento">
    <i class="fa-solid fa-circle-plus fa-lg"></i> Crear Elemento
</button>
```

### Botón de Editar
```html
<button class="btn btn-warning"
        onclick="postToExternalSite('index.php', { 
            opcion: 'editar_elemento', 
            pk: '<?php echo $id; ?>', 
            tabla: 'tabla', 
            pkname: 'pk_tabla'
        });">
    <i class="fa-solid fa-pencil"></i>Editar
</button>
```

### Botón de Eliminar/Desactivar
```html
<button class="btn btn-danger"
        onclick="postToExternalSite('index.php', { 
            opcion: 'desactivar', 
            pk: '<?php echo $id; ?>', 
            tabla: 'tabla', 
            pkname: 'pk_tabla'
        });">
    <i class="fa-solid fa-trash"></i> Desactivar 
</button>
```

### Botón de Activar
```html
<button class="btn btn-primary"
        onclick="postToExternalSite('index.php', { 
            opcion: 'activar', 
            pk: '<?php echo $id; ?>', 
            tabla: 'tabla', 
            pkname: 'pk_tabla'
        });">
    <i class="fa-solid fa-check"></i> Activar
</button>
```

## 📊 Tablas

### Estructura de Tabla
```html
<div id="tabla-catalogo" class="w-100">
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th>Columna 1</th>
                <th>Columna 2</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-body">
            <!-- Contenido dinámico desde PHP -->
        </tbody>
    </table>
</div>
```

### Paginación
```html
<div id="paginacion-container" class="pagination-container text-center mt-4">
    <div class="d-flex justify-content-center align-items-center">
        <button class="btn btn-primary btn-sm me-2" id="btn-primero" disabled>
            <i class="fa-solid fa-angles-left"></i> Primero
        </button>
        <button class="btn btn-primary btn-sm me-2" id="btn-anterior" disabled>
            <i class="fa-solid fa-angle-left"></i> Anterior
        </button>
        <span id="pagina-info" class="mx-3 badge badge-activo">Página 1</span>
        <input type="text" id="pagina-input" value="1" class="form-control" 
               style="width: 60px; text-align: center; display: inline-block;">
        <span id="registro-info" class="mx-3 badge badge-inactivo"></span>
        <button class="btn btn-primary btn-sm ms-2" id="btn-siguiente">
            Siguiente <i class="fa-solid fa-angle-right"></i>
        </button>
        <button class="btn btn-primary btn-sm ms-2" id="btn-ultimo">
            Último <i class="fa-solid fa-angles-right"></i>
        </button>
    </div>
</div>
```

## 🎯 Iconos Recomendados (Font Awesome)

### Para Campos
- Usuario: `fas fa-user`
- Email: `fas fa-envelope`
- Teléfono: `fas fa-phone`
- Dirección: `fas fa-map-marker-alt`
- Fecha: `fas fa-calendar`
- Número: `fas fa-hashtag`
- Texto: `fas fa-align-left`
- Lista: `fas fa-list`
- Categoría: `fas fa-tag`
- Descripción: `fas fa-comment`

### Para Acciones
- Guardar: `fas fa-save`
- Editar: `fas fa-pencil`
- Eliminar: `fas fa-trash`
- Activar: `fas fa-check`
- Crear: `fas fa-circle-plus`
- Salir: `fas fa-times`
- Ver: `fas fa-eye`
- Buscar: `fas fa-search`

## 🚀 Cómo Crear un Nuevo Formulario

### Paso 1: Copia la estructura básica
```html
<div class="mango-container">
    <div align="center">
        <div class="alert alert-primary mt-5" role="alert">
            <h1 id="titulo">Tu Nuevo Formulario</h1>
        </div>
    </div>
    
    <div class="separator-mango"></div>
    
    <div id="formulario-alta" style="display: none;">
        <form id="form-alta" method="POST" onsubmit="enviarFormulario(event)">
            <div class="row">
                <!-- Aquí van tus campos -->
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save"></i>Guardar
                </button>
                <button class="btn btn-danger" type="button" onclick="ocultarFormulario()">
                    <i class="fas fa-times"></i>Salir
                </button>
            </div>
        </form>
    </div>
    
    <div id="tabla-catalogo" class="w-100">
        <!-- Tu tabla aquí -->
    </div>
    
    <div class="section-footer">
        <p>Sistema de Gestión - Empacadora de Mango 🥭</p>
    </div>
</div>
```

### Paso 2: Agrega tus campos específicos
- Usa los ejemplos de campos mostrados arriba
- Mantén la estructura `form-group mb-3`
- Incluye iconos apropiados en los labels
- Usa placeholders descriptivos

### Paso 3: Configura la tabla
- Copia la estructura de tabla mostrada
- Personaliza las columnas según tus necesidades
- Mantén la paginación si tienes muchos registros

### Paso 4: No olvides incluir
- Los scripts JavaScript necesarios (`proceso_tabla.js`)
- Los campos hidden para el manejo de datos
- Las funciones PHP en el controlador correspondiente

## 🎨 Clases Auxiliares Disponibles

### Colores de Texto
- `.text-mango-yellow` - Texto amarillo
- `.text-mango-green` - Texto verde
- `.text-mango-orange` - Texto naranja

### Fondos
- `.bg-mango-yellow` - Fondo amarillo
- `.bg-mango-green` - Fondo verde
- `.bg-mango-orange` - Fondo naranja
- `.bg-gradient-mango` - Gradiente amarillo
- `.bg-gradient-verde` - Gradiente verde

### Bordes
- `.border-mango-yellow` - Borde amarillo
- `.border-mango-green` - Borde verde
- `.border-mango-orange` - Borde naranja

### Efectos
- `.hover-lift` - Efecto de elevación al hacer hover

## 📱 Responsive Design
El diseño es completamente responsive y se adapta a:
- Desktop (> 768px)
- Tablet (768px - 480px)  
- Mobile (< 480px)

## ✨ Características Especiales
- **Animaciones suaves**: Transiciones en hover, focus y click
- **Estados visuales**: Feedback inmediato en interacciones
- **Validación visual**: Colores que indican estados válidos/inválidos
- **Accesibilidad**: Contrastes adecuados y navegación por teclado
- **Consistencia**: Diseño unificado en todo el sistema

## 🔧 Personalización
Para personalizar colores o estilos:
1. Modifica las variables CSS en `:root` del archivo `mostrar-mango.css`
2. Todos los elementos se actualizarán automáticamente
3. Mantén la consistencia usando las variables definidas

---
**¡Listo!** Con esta guía puedes crear formularios consistentes y atractivos para todo tu sistema. El diseño se aplicará automáticamente a cualquier formulario que uses esta estructura.
