Plataforma de e-Commerce con Microservicios
Este proyecto es una implementación de una plataforma de e-commerce utilizando una arquitectura de microservicios. Cada microservicio maneja una responsabilidad específica, como la gestión de productos, carritos de compras, pedidos, inventario y notificaciones. La comunicación entre los microservicios se realiza mediante RabbitMQ y Redis, asegurando una arquitectura desacoplada y escalable.

Configuración inicial basada en el curso de Microservicios con Docker y Symfony de Codenip en youtube https://www.youtube.com/watch?v=6UC4U-ne6Gw&list=PLWpsZlKx38t9JwtYixQ_D7t_U1kGeqHkB

Tecnologías utilizadas:
Symfony
Docker
RabbitMQ
Redis
MySQL
Este proyecto sigue los principios SOLID y está diseñado para ser mantenible y extensible.

Instalación


composer create-project symfony/skeleton project ^6.4
mv project/* .