<?php
while ($row = pg_fetch_assoc($cosultaTanquesZoocriadero)) {

    echo "<tr>";
        echo "<td>" . $row['nombre_zoocriadero'] . "</td>";
        echo "<td>" . $row['estado_zoocriadero'] . "</td>";
        echo "<td>" . $row['id_tanque'] . "</td>";
        echo "<td>" . $row['nombre_tipo_tanque'] . "</td>";
    echo "</tr>";
}

?>