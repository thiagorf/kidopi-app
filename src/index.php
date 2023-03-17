<?php
    require('database.php');

    if (isset($_POST["country"])) {
        
        $url = "https://dev.kidopilabs.com.br/exercicio/covid.php?pais="; 

        $ch = curl_init($url + $_POST["country"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $res = json_decode($res);
        curl_close($ch);

        # total deaths and confirmed cases
        # log country and datetime in each query
        # footer needs the last log entry
        echo $res;
        $totalCases = 0;
        $totalDeaths = 0;

        foreach($res as $c) {
            $totalCases += $c["Confirmados"];
            $totalDeaths += $c["Mortos"];
        }
        
        $stmt = $conn->prepare("INSERT INTO log (country) VALUES (?)");
        $stmt->execute([$_POST["country"]]);
        
    }   
        $st = $conn->query("SELECT created_at FROM log ORDER BY created_at DESC LIMIT 1");
        $lastAccess = $st->fetchColumn();
?>

<html>
    <body>
        <form action="" method="POST">
            <select name="country'">
                <option value="Brazil">Brazil</option>
                <option value="Canada">Canada</option>
                <option value="Australia">Australia</option>
            </select>
        <form>
        <?php if ($data):  ?>
        <table>
            <thead>
                <tr>
                    <td>Estado</td>
                    <td>Casos confirmadas</td>
                    <td>Pais</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $d): ?>
                    <tr>
                        <td><?=$d["ProvinciaEstado"]?></td>
                        <td><?=$d["Confirmados"]?></td>
                        <td><?=$d["Mortos"]?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <p>Total casos confirmados: <?= $totalCases ?>, mortes totais confirmadas: <?= $totalDeaths?></p>
        <?php endif ?>
        <footer>Ultimo acesso <?= $lastAccess ? $lastAccess : "não existe registro de acesso."  ?></footer>
    </body>
</html>
