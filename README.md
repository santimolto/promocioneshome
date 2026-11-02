# Santi Promociones Home

Módulo Magento 2 para gestionar dinámicamente el bloque **Promociones** del home desde el backend.

Está basado en la extensión `Santi_HomeConcurso`, pero adaptado a:

```txt
Santi_PromocionesHome
```

Repositorio previsto:

```txt
https://github.com/santimolto/promocioneshome
```

Paquete Composer:

```txt
santi/module-promocioneshome
```

---

## Características

- Configuración desde `Santi Extensions > Promociones`
- Compatible con multistore
- Imagen o vídeo
- Imagen y poster específicos para móvil (opcionales)
- Poster para vídeo
- Título
- Subtítulo
- Botón
- Color primario configurable
- Fallback si no hay configuración activa
- Instalación en `vendor/santi/module-promocioneshome`

---

## Ruta de subida de media

```txt
pub/media/santi_promocioneshome/
```

---

## Instalación por Composer

Desde la raíz de Magento:

```bash
composer config repositories.santi-promocioneshome vcs https://github.com/santimolto/promocioneshome
composer require santi/module-promocioneshome:dev-main
php bin/magento setup:upgrade
php bin/magento cache:flush
```

Si estás en producción:

```bash
php bin/magento setup:di:compile
```

---

## Actualización

```bash
composer clear-cache
composer update santi/module-promocioneshome -W
php bin/magento setup:upgrade
php bin/magento cache:flush
```

---

## Configuración

En el backend:

```txt
Stores > Configuration > Santi Extensions > Promociones
```

Campos disponibles:

- Habilitado
- Imagen
- Imagen móvil (opcional, < 768px)
- Alt de la imagen
- Vídeo
- Poster vídeo / fallback
- Poster vídeo móvil (opcional)
- Título
- Subtítulo
- Texto del botón
- URL del botón
- Color primario hex

---

## Template Hyvä

El módulo incluye un template de ejemplo en:

```txt
docs/home_promociones.phtml
```

Cópialo a tu tema:

```txt
app/design/frontend/Mb/Hyva2/Magento_Theme/templates/home/promociones.phtml
```

Uso del ViewModel:

```php
/** @var \Hyva\Theme\Model\ViewModelRegistry $viewModels */
$vm = $viewModels->require(\Santi\PromocionesHome\ViewModel\Promociones::class);
$banner = $vm->getBanner();
```

---

## Notas

- Si se configura vídeo, el vídeo tiene prioridad sobre la imagen.
- Si no hay vídeo ni imagen, el template usa fallback.
- Si la URL del botón no empieza por `http`, `https` o `/`, se convierte automáticamente en URL absoluta usando la base URL del store actual.
- **Imagen móvil:** si se sube, se muestra en pantallas de menos de 768px mediante `<picture>`; si no, se usa la imagen de escritorio. Recomendado formato vertical (p. ej. 1080×1350).
- **Poster móvil:** si hay vídeo y poster móvil (o imagen móvil), el template pinta un `<picture>` responsive detrás del vídeo, porque el atributo `poster` no admite versiones por breakpoint.
