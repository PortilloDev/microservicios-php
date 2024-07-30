# Plataforma de e-Commerce con Microservicios

## Descripción del Proyecto

Este proyecto es una plataforma de e-commerce construida utilizando una arquitectura de microservicios. La plataforma permite a los usuarios navegar por productos, agregar artículos a su carrito de compras, realizar pedidos y recibir notificaciones de confirmación. Cada funcionalidad principal está implementada como un microservicio independiente, asegurando una alta escalabilidad y mantenibilidad del sistema.

El proyecto utiliza RabbitMQ para la comunicación entre microservicios y Redis para el almacenamiento en caché, lo que permite una respuesta rápida y eficiente.

## Tecnologías Usadas

- **Lenguaje**: PHP (Symfony)
- **Bases de Datos**: MySQL
- **Mensajería**: RabbitMQ
- **Caché**: Redis
- **Contenerización**: Docker, Docker Compose
- **Otros**: Nginx, PHPUnit

## Instalación

Para configurar el proyecto localmente, sigue estos pasos:

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/tu_usuario/nombre_del_proyecto.git
   cd nombre_del_proyecto

Configurar las variables de entorno:
Copia el archivo .env.example a .env y configura las variables necesarias, como la conexión a la base de datos, Redis y RabbitMQ.

Construir y arrancar los contenedores Docker:

bash
Copiar código
docker-compose up -d
Instalar dependencias:
Ejecuta los siguientes comandos dentro de los contenedores de los microservicios para instalar las dependencias:

bash
Copiar código
docker exec -it nombre_del_contenedor composer install
Migrar las bases de datos:
Ejecuta las migraciones para configurar las bases de datos:

bash
Copiar código
docker exec -it nombre_del_contenedor php bin/console doctrine:migrations:migrate
Acceder a la aplicación:
La aplicación estará disponible en http://localhost (o el puerto configurado en Docker).

Casos de Uso
1. Registro de Usuarios
   Los usuarios pueden registrarse en la plataforma proporcionando su nombre y correo electrónico. Una vez registrados, reciben un correo de confirmación para verificar su cuenta.

2. Gestión de Productos
   Los administradores pueden añadir, actualizar o eliminar productos en el catálogo, así como consultar el inventario disponible. Los productos se muestran en el frontend para que los usuarios los naveguen.

3. Carrito de Compras
   Los usuarios pueden agregar productos a su carrito, ajustar las cantidades y ver el total actualizado en tiempo real. El carrito se guarda en la sesión del usuario y se puede acceder desde cualquier dispositivo.

4. Proceso de Pedido
   Después de revisar el carrito, los usuarios pueden proceder al pago y realizar un pedido. El sistema verifica la disponibilidad de inventario y confirma el pedido, enviando una notificación por correo electrónico.

5. Notificaciones
   El sistema envía notificaciones por correo electrónico a los usuarios para confirmar sus acciones, como el registro de cuenta o la realización de pedidos.

Para más detalles, consulta la documentación completa del proyecto o explora el código fuente en GitHub.


# Plataforma de e-Commerce con Microservicios

Este proyecto es una implementación de una plataforma de e-commerce utilizando una arquitectura de microservicios. Cada microservicio maneja una responsabilidad específica, como la gestión de productos, carritos de compras, pedidos, inventario y notificaciones. La comunicación entre los microservicios se realiza mediante RabbitMQ y Redis, asegurando una arquitectura desacoplada y escalable.

## Objetivos
Configuración inicial basada en el curso de Microservicios con Docker y Symfony de Codenip en youtube https://www.youtube.com/watch?v=6UC4U-ne6Gw&list=PLWpsZlKx38t9JwtYixQ_D7t_U1kGeqHkB

Tecnologías utilizadas:
* PHP 8.1
* Symfony 6.4
* Docker
* RabbitMQ
* Redis
* MySQL
Este proyecto sigue los principios SOLID y está diseñado para ser mantenible y extensible.

## Instalación

### Creación estructura symfony

composer create-project symfony/skeleton project ^6.4
mv project/* .

### Librerias o dependencias a instalar
```
composer require monolog symfony/ampq-messenger symfony/validator doctrine/orm symfony/serializer symfony/property-access symfony/uid

composer require symfony/maker-bundle --dev 

composer require symfony/serializer-pack symfony/orm-pack symfony/security-bundle symfony/expression-language guzzlehttp/guzzle

```
### Configuración de RabbitMQ
Se reinician los contenedores de Docker y se ejecuta el comando
Para configurar los transportes.
```
sf messenger:setup-transports 
```

Para consumir los mensajes:
```
sf messenger:consume async_register_application
```

### Revisar la url de ngrock
```
http://localhost:4050
```
