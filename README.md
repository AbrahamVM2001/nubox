# Sistema Nubox

**Primera versión del sistema Nubox para un concurso de programación**, donde se presentó y se obtuvo el tercer lugar. Este proyecto tiene como objetivo facilitar el registro de la renta de salones y oficinas, así como la generación y descarga de contratos en formato PDF.

## Versiones

| **Versión**   | **Descripción**                                                                                      | **Características**                                                                                                                                   | **Tecnologías**                                                                                   | **Mejoras Implementadas**                                                                | **Funcionalidades**                                                                                                                                                  | **Fecha**            |
|---------------|------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------------|
| **Versión 1.0** | Primera versión del sistema para el concurso.                                                       | - Paleta de colores representativos de México.<br>- Diseño responsivo.<br>- Registro de la renta de salones y oficinas.<br>- Descarga del PDF.<br>- Generador de PDF con impresión de pantalla. | - PHP<br>- Bootstrap<br>- MySQL<br>- HTML<br>- CSS<br>- JavaScript                                   | - Registro de la renta.<br>- Generación de PDF.<br>- Diseño responsivo.<br>- Descarga del contrato de renta.            | - Generador de reporte por PDF.<br>- Pasarela de pagos.                                                                                                                                 | 28 de junio 2023     |
| **Versión 2.0** | Se realizaron cambios importantes en el diseño y la funcionalidad tanto en el front como en el back. | - Diseño responsivo.<br>- Cambio de la paleta de colores.                                                                                           | - PHP<br>- MySQL<br>- Bootstrap<br>- HTML<br>- CSS<br>- JavaScript<br>- MVC                         | - Arreglo de diseño responsivo.<br>- Cambio de la paleta de colores.                                           | - Pasarela de pagos.<br>- Generador de PDF.<br>- Envío de correos.<br>- Envío de correos con reporte. | (A definir según lanzamiento) |

## Características

- **Diseño Responsivo**: Se adaptó a diferentes dispositivos, asegurando una excelente experiencia de usuario tanto en computadoras como en móviles y tabletas.
- **Generación de Contratos en PDF**: Permite la descarga y generación automática de contratos de renta.
- **Registro de Renta**: Registro de salones y oficinas para su alquiler.
- **Pasarela de Pagos**: Integración para el procesamiento de pagos online.
- **Envío de Correos**: Notificaciones automáticas y envío de reportes por correo electrónico.

## Tecnologías Utilizadas

- **PHP**: Para la lógica del servidor.
- **Bootstrap**: Framework de CSS para el diseño responsivo.
- **MySQL**: Base de datos para almacenar la información de rentas y usuarios.
- **HTML/CSS**: Para la estructura y el diseño de la página.
- **JavaScript**: Para la interacción en el frontend.
- **MVC**: Arquitectura de desarrollo utilizada en la versión 2.0.

## Mejoras y Funcionalidades

### Version 1.0
- Implementación inicial con funcionalidades clave como la **generación de contratos en PDF** y **registro de rentas**.
- Diseño basado en colores representativos de México.

### Version 2.0
- Mejoras en el **diseño responsivo** para una experiencia de usuario más fluida.
- **Cambio de paleta de colores** para una interfaz más moderna.
- Implementación del patrón **MVC** para mejorar la escalabilidad del sistema.
- Nuevas funcionalidades como el **envío de correos** con los reportes y la **pasarela de pagos**.

## Instalación

Sigue estos pasos para configurar el sistema en tu entorno local:

1. **Clona el repositorio**:

    ```bash
    git clone https://github.com/AbrahamVM2001/punto_venta
    ```

## Contribuir

Si deseas contribuir al proyecto, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una rama para tus cambios:
    ```bash
    git checkout -b nueva-funcionalidad
    ```
3. Realiza tus cambios y haz commit:
    ```bash
    git commit -am 'Descripción de los cambios'
    ```
4. Haz push a tu rama:
    ```bash
    git push origin nueva-funcionalidad
    ```
5. Abre un pull request.

## Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.

## Autores

- [Abraham VM](https://github.com/AbrahamVM2001)
