<?php
    require('database.php');
    
    $res = null;

    if (isset($_POST["country"])) {
        
        $url = "https://dev.kidopilabs.com.br/exercicio/covid.php?pais="; 

        $ch = curl_init($url . $_POST["country"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $res = json_decode($res, true);
        curl_close($ch);

        # total deaths and confirmed cases
        # log country and datetime in each query
        # footer needs the last log entry
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
    <head>
        <title>Kidopi</title>
         <link rel="stylesheet" href="./css/global.css"> 
         <link rel="stylesheet" href="./css/table.css"> 
         <link rel="stylesheet" href="./css/footer.css"> 
         <link rel="stylesheet" href="./css/input.css"> 
    </head>
    <body>
        <div class="container">
            <div class="form-container">
                <form action="" method="POST">
                    <select name="country">
                        <option value="Brazil">Brazil</option>
                        <option value="Canada">Canada</option>
                        <option value="Australia">Australia</option>
                    </select>
                    <button type="submit">pesquisar</button>
                <form>
            </div>
            <?php if ($res):  ?>
            <div class="table-container">
                <table id="t">
                    <thead>
                        <tr class="table-header">
                            <th>Estado</th>
                            <th>Casos confirmados</th>
                            <td>Mortes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($res as $d): ?>
                            <tr>
                                <td><?=$d["ProvinciaEstado"]?></td>
                                <td><?=$d["Confirmados"]?></td>
                                <td><?=$d["Mortos"]?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <p id="total">
                    Total casos confirmados: 
                    <span class="total-highlight">
                        <?= $totalCases ?>
                    </span>
                    , mortes totais confirmadas: 
                    <span class="total-highlight">
                        <?= $totalDeaths?>
                    </span>
                </p>

             </div>
            <?php endif ?>
            <footer>Ultimo acesso <?= $lastAccess ? $lastAccess : "não existe registro de acesso."  ?></footer>
        </div>
    </body>
</html>
