# Proyecto Valenbisi con Base de Datos en la Nube (AWS)

## Datos del Alumno
* **Nombre:** Branko Akerman
* **Curso:** 1º DAW
* **Proyecto:** ValenbiciAPI26T8 (Mejora de la Tarea 3 con AWS)

---

## Descripción del Proyecto
Este proyecto es una evolución de la aplicación de gestión de **Valenbisi** desarrollada en la Tarea 3. Se ha modificado la persistencia de la aplicación para que, en lugar de almacenar o consultar los datos de forma local, interactúe directamente con una base de datos relacional alojada en la nube mediante **Amazon RDS (AWS)**. El acceso se realiza de forma remota utilizando el conector JDBC de MySQL.

---

## Requisitos de la Entrega y Evidencias

### 1. Conexión de la Base de Datos en MySQL Workbench
A continuación, se muestra la configuración y conexión exitosa a nuestra instancia de AWS RDS desde el gestor de bases de datos MySQL Workbench, validando que el puerto 3306 está abierto y accesible:



### 2. Comprobación del Funcionamiento de la Aplicación y Páginas
En esta sección se evidencia que la aplicación Java se conecta correctamente a la base de datos de AWS y que los datos se muestran y cargan perfectamente en las pantallas/páginas del sistema:



### 3. Proyecto subido a GitHub
El código fuente completo de este proyecto ha sido versionado y subido a un repositorio público de GitHub para su entrega. Aquí se muestra la estructura del proyecto en la plataforma:



---

## Configuración del Entorno
* **Base de Datos:** MySQL / MariaDB en Amazon RDS
* **Driver JDBC:** Configurado como dependencia en el archivo `pom.xml` (Maven)
* **IDE:** Eclipse