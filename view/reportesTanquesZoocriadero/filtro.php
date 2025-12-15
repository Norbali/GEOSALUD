<?php
    while ($row = pg_fetch_assoc($cosultaTanquesZoocriadero)) {

        echo "<tr>";
            echo "<td>" . $row['nombre_zoocriadero'] . "</td>";
            echo "<td>" . $row['estado_zoocriadero'] . "</td>";
            echo "<td>" . $row['id_tanque'] . "</td>";
            echo "<td>" . $row['nombre_tipo_tanque'] . "</td>";
        echo "</tr>";
    }


    if (isset($_SESSION['sinResultados'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ' . $_SESSION['sinResultados'] . '
                
            </div>';
        unset($_SESSION['sinResultados']);
    }
?>