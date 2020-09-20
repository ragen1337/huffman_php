<?php
require_once "textHandler.php";
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Huffman method: Result</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div id="page">
            <header>
                <h1>Your encoded text!</h1>
            </header>
            <main>
                <div id="infoBlock">
                    <textarea cols="90" rows="25" wrap="hard"><?= $encoded_text ?></textarea>
                </div>
                <h1>Dictionary</h1>
                <div id="tableBlock">
                    <table>
                        <th>
                            Letter
                        </th>
                        <th>
                            Code
                        </th>
                        <?=$table['table_string']?>
                    </table>

                    <a href="/">Back</a>
                </div>
            </main>
        </div>
    </body>
</html>
