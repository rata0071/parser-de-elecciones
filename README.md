# Parser basico para los resultados de las elecciones 2013

## Bajar datos

Primero hacete un mirror de la parte que quieras de los resultados

  httrack http://www.resultados.gob.ar/telegramas/IMUN04.htm --mirror --keep-alive --disable-security-limits

## Extraer datos

Larga un csv con los votos (modificar a gusto)

  php extract.php

## Escanear resultados

Revisa todas las mesas buscando alguna inconsistencia (lo mismo) 

  php scan.php


