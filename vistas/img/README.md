# Instrucciones para Imagen de Fondo

## Cómo añadir tu imagen de fondo de mangos

1. **Guarda tu imagen** en esta carpeta con el nombre: `mango-background.jpg`
   - Ruta completa: `/vistas/images/mango-background.jpg`

2. **Especificaciones recomendadas:**
   - Formato: JPG, PNG o WebP
   - Resolución mínima: 1920x1080px
   - Tamaño optimizado: menos de 500KB para carga rápida
   - Orientación: Horizontal (landscape)

3. **Si quieres usar un nombre diferente:**
   - Edita el archivo `/vistas/css/pagina-principal.css`
   - Busca la línea: `background-image: url('../images/mango-background.jpg');`
   - Cambia `mango-background.jpg` por el nombre de tu archivo

## Alternativas de Configuración

### Opción 1: Imagen desde URL externa
```css
.hero-section {
    background-image: url('https://tu-dominio.com/imagen-mangos.jpg');
}
```

### Opción 2: Múltiples imágenes (responsive)
```css
.hero-section {
    background-image: url('../images/mango-background-mobile.jpg');
}

@media (min-width: 768px) {
    .hero-section {
        background-image: url('../images/mango-background-desktop.jpg');
    }
}
```

### Opción 3: Gradiente sin imagen
```css
.hero-section {
    background: linear-gradient(135deg, #ff6347, #ff8c00, #ffd700);
}
```

## Optimización de Rendimiento

- Usa herramientas como TinyPNG para comprimir la imagen
- Considera usar formato WebP para mejor compresión
- Añade `loading="lazy"` si usas etiquetas `<img>`
