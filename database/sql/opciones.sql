-- INSERT INTO `opcions` (`id`, `nombre`, `valor`, `pregunta_id`) VALUES
-- 	(1, 'No existe Equipo constituido formalmente.', 0, 3),
-- 	(2, 'Equipo constituido formalmente, pero  no se han asignado funciones', 1, 3),
-- 	(3, 'Equipo constituido formalmente,  y se han asignado funciones', 2, 3),
-- 	(4, 'No cuenta ', 0, 4),
-- 	(5, 'Trámite en proceso ', 1, 4),
-- 	(6, 'Resolución de categorización vigente ', 2, 4),
-- 	(7, 'No cuenta ', 0, 5),
-- 	(8, 'Cumple con mas de dos  items ', 1, 5),
-- 	(9, 'Cumple con mas de  3 items ', 2, 5);

-- INSERT INTO `items` (`id`, `nombre`, `pregunta_id`) VALUES
-- 	(1, 'Se reúne con el equipo de trabajo mensualmente para evaluación de avance de indicadores y otros (acta)', 5),
-- 	(2, 'Cumple con   lo  programado  en su  Plan Anual de Trabajo (ver fecha).', 5),
-- 	(3, 'Cumple con la programacion de actividades intra y extramuro.', 5),
-- 	(4, 'Demuestra la implementación de medidas correctivas tras la evaluación del Plan Anual de Trabajo e Indicadores de Desempeño.', 5);

-- INSERT INTO `criterios` (`id`, `tipo`, `minimo`, `maximo`, `exacto`, `opcion_id`) VALUES
-- 	(1, '=', NULL, NULL, 0, 7),
-- 	(2, '>', NULL, NULL, 2, 8),
-- 	(3, '>', NULL, NULL, 3, 9);