<?php
/* @var $model app\models\ConsultaModel */
use app\models\Paper;
?>

<p> Você inseriu as seguintes informações: </p>

<ul>
	<li><label>Nome</label>: <?= $model->nome ?></li>
	<li><label>Data Inicial</label>: <?= $model->inicio ?></li>
	<li><label>Data Final</label>: <?= $model->final ?></li>
</ul>

<html>
    <head>

    </head>

    <body>
        <table>
            <tr>
                <td>Nome</td>
                <td>Preço Ab.</td>
                <td>Preço Fe.</td>
                <td>Data</td>
            </tr>
        <?php
        $paper = Paper::find()->where(["codneg"=>$model->nome])->all();

        foreach($paper as $p)
            echo "<tr>
                <td>$p->nomres</td>
                <td>$p->preab</td>
                <td>$p->preult</td>
                <td>$p->date</td>
            </tr>";
        ?>

        </table>
    </body>

</html>